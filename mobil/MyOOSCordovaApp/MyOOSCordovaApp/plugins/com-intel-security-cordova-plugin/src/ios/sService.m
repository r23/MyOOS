/******************************************************************************
"Copyright (c) 2015-2015, Intel Corporation
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation
    and/or other materials provided with the distribution.
3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this
    software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED
WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
"
******************************************************************************/

#ifndef DEBUG
#ifndef NDEBUG
#define NDEBUG
#endif
#endif

#import "sService.h"
#import "xss_api.h"
#import "xss_error.h"
#import "xss_types.h"

#ifndef NDEBUG
#import "xss_log.h"
#endif

#import <Foundation/Foundation.h>


@interface WhitelistConfigParser : NSObject <NSXMLParserDelegate> {}

@property sservice_size_t config_access_id;
@property sservice_size_t config_subdomains_id;
@property sservice_result_t res;

@end

@implementation WhitelistConfigParser

- (id)init
{
    self = [super init];
    self.config_access_id = CONFIG_ID_WHITELIST_ACCESS_MIN;
    self.config_subdomains_id = CONFIG_ID_WHITELIST_SUBDOMAINS_MIN;
    self.res = SSERVICE_SUCCESS_NOINFO;
    return self;
}


- (sservice_result_t)getErrorCode
{
    return self.res;
}

- (void)parser:(NSXMLParser*)parser didStartElement:(NSString*)elementName namespaceURI:(NSString*)namespaceURI qualifiedName:(NSString*)qualifiedName attributes:(NSDictionary*)attributeDict
{
    
    if (([elementName isEqualToString:@"access"]) &&
        (self.config_access_id <= CONFIG_ID_WHITELIST_ACCESS_MAX) &&
        (IS_SUCCESS(self.res)))
    {
        
        NSString* origin = [attributeDict valueForKey:@"origin"];
        if (origin != nil)
        {
            self.res = sservice_config_set((config_id_enum_t)self.config_access_id, [origin UTF8String], (sservice_size_t)[origin length]+1);
            
            if (IS_SUCCESS(self.res))
            {
                NSString* subdomains = [attributeDict valueForKey:@"subdomains"];
                if (subdomains != nil)
                {
                    self.res = sservice_config_set((config_id_enum_t)self.config_subdomains_id, [subdomains UTF8String], (sservice_size_t)[subdomains length]+1);
                }
            }
            self.config_access_id++;
            self.config_subdomains_id++;
        }
    }
}

@end


#pragma mark - APIs

@implementation sService

#ifndef NDEBUG
#define XSSLOG_BRIDGE( log_level, format_str, ...)  sservice_log( LOG_SOURCE_BRIDGE, log_level, format_str, ##__VA_ARGS__)
#else
static inline void DoNothing(char const * formatStr, ... )
{
}
#define XSSLOG_BRIDGE( log_level, format_str, ...) DoNothing(format_str,  ##__VA_ARGS__)
#endif

#define STRING_ENCODING (NSUTF8StringEncoding)

-(NSInteger)getIntFromArgument: (CDVInvokedUrlCommand *)command
					argNumber :(NSInteger)arg
{
    if( strcmp( object_getClassName([command.arguments objectAtIndex:arg]), "__NSCFNumber" ) != 0)
    {
        return 0 ;
    }
    
    NSNumber *obj = [command.arguments objectAtIndex:arg] ;
	if (obj != nil)
    {
        NSInteger Data = (NSInteger)[obj doubleValue];
        return Data;
    }
	XSSLOG_BRIDGE(LOG_ERROR, "%s:Error access parameters", __FUNCTION__ ) ;
	return 0 ;
}

-(sservice_handle_t)getHandleFromArgument: (CDVInvokedUrlCommand *)command argNumber :(NSInteger)arg
{
	if( strcmp( object_getClassName([command.arguments objectAtIndex:arg]), "__NSCFNumber" ) != 0)
    {
        return 0 ;
    }
    NSNumber *obj = [command.arguments objectAtIndex:arg] ;
	if (obj != nil)
    {
	    sservice_handle_t DataHandle = [obj doubleValue];
		return DataHandle;
	}
	XSSLOG_BRIDGE(LOG_ERROR, "%s:Error access parameters", __FUNCTION__ ) ;
	return 0 ;
}

-(bool)checkArguments: (CDVInvokedUrlCommand *)command argNumber :(NSInteger)arg
{
	if(!command )
	{
		return false ;
	}
    if( [command.arguments count ]!= arg)
    {
        return false ;
    }
    for( int i = 0; i < arg; i++ )
    {
        if( [command.arguments objectAtIndex:i ] == nil)
        {
            return false;
        }
    }
    return true ;
}


- (sservice_result_t ) getInternalPath
{
    // get the documents directory;
    // first path in array is "document directory" .
    NSArray *paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES);
    if( !paths )
    {
    	return SSERVICE_ERROR_INTERNAL_ERROR ;
    }
    NSString *documentsDirectory = [paths objectAtIndex:0];
    if(!documentsDirectory)
    {
    	return SSERVICE_ERROR_INTERNAL_ERROR;
    }
    
    return sservice_config_set(CONFIG_ID_LOCAL_PATH, [documentsDirectory UTF8String], (sservice_size_t)[documentsDirectory length]+1 ) ;
}


- (void) GlobalInit:(CDVInvokedUrlCommand *)command
{
    sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
    XSSLOG_BRIDGE(LOG_INFO, "%s:start", __FUNCTION__);

    res = sservice_global_init_start() ;
    
    if( IS_SUCCESS(res) )
    {
        //this call will move the rest of the procedure to another thread
        [self.commandDelegate runInBackground:^{
            
            sservice_result_t res = [self getInternalPath ] ;
            if( IS_SUCCESS(res) )
            {
                NSString* appName = NSBundle.mainBundle.infoDictionary  [@"CFBundleDisplayName"];
                res = sservice_config_set(CONFIG_ID_APP_ID, [appName UTF8String], (sservice_size_t)[appName length]+1 ) ;
            }
            if( IS_SUCCESS(res) )
            {
                NSString *strApplicationUUID = [[[UIDevice currentDevice] identifierForVendor] UUIDString];
                res = sservice_config_set(CONFIG_ID_HARDWARE_ID, [strApplicationUUID UTF8String], (sservice_size_t)[strApplicationUUID length]+1 ) ;
            }
            if( IS_SUCCESS(res) )
            {
                NSString *path = [[NSBundle mainBundle] pathForResource:@"config" ofType:@"xml"];
                NSURL *url = [NSURL fileURLWithPath:path]; //here you have to pass the filepath
                NSXMLParser *parser = [[NSXMLParser alloc] initWithContentsOfURL :url];
                WhitelistConfigParser *whitelistConfigParser = [[WhitelistConfigParser alloc] init];
                    
                [parser setDelegate:whitelistConfigParser];
                // Invoke the parser and check the result
                [parser parse];
                    
                res = [whitelistConfigParser getErrorCode ];
            }
            if( IS_SUCCESS(res) )
            {
                res = sservice_global_init_end() ;
            }
            CDVPluginResult *pluginResult = NULL ;
            if( IS_FAILED(res) )
            {
                pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:res.error_or_warn_code];
                XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s end, error 0x%x", __FUNCTION__, res.error_or_warn_code ) ;
            }
            else
            {
                XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
                pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK ];
            }
            [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
            
        }];
    } else {
        XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s start, error 0x%x", __FUNCTION__, res.error_or_warn_code ) ;
        CDVPluginResult* pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:res.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }
}

/** Function is callback for cordova call of
 *		cordova.exec(success, failInternal, "IntelSecurity", "SecureDataCreateFromData",
 *					[defaults.data, defaults.tag, defaults.appAccessControl, defaults.deviceLocality, defaults.sensitivityLevel, defaults.creator, defaults.owners]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureDataCreateFromData:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    
    if( ![self checkArguments:command argNumber:11])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return ;
	}
    
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        
        //Retrieve all necessary parameters.
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        NSString *dataStr = NULL;
        NSString *tagStr  = NULL;
        NSString *trustedWebDomainsStr = NULL ;
        sservice_data_handle_t extraKey= 0;
        NSInteger appAccessControl= 0;
        NSInteger deviceLocality= 0;
        NSInteger sensitivityLevel = 0;
        NSInteger creator= 0;
        NSInteger noStore= 0;
        NSInteger noRead= 0;
        NSArray *owners= NULL;
        dataStr = [command.arguments objectAtIndex:0];
        tagStr = [command.arguments objectAtIndex:1];
        extraKey = [self getHandleFromArgument: command argNumber: 2 ];
        appAccessControl= [ self getIntFromArgument:command argNumber:3 ];
        deviceLocality= [ self getIntFromArgument:command argNumber: 4 ];
        sensitivityLevel = [self getIntFromArgument:command argNumber: 5 ];
        noStore = [ self getIntFromArgument:command argNumber:6 ];
        noRead = [ self getIntFromArgument:command argNumber:7 ];
        creator= [self getIntFromArgument:command argNumber: 8 ];
        owners= [command.arguments objectAtIndex:9];
        trustedWebDomainsStr = [command.arguments objectAtIndex:10];
        if(!dataStr || !owners)
        {
            res = SSERVICE_ERROR_INVALIDPOINTER ;
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, error 0x%x", __FUNCTION__, res  ) ;
        }
        
        //Convert all parameters for C code types
        unsigned long owners_num  = 0;
        sservice_persona_id_t *owners_list = NULL ;
        if( IS_SUCCESS(res))
        {
            owners_num = [owners count ];
            owners_list = calloc( sizeof( sservice_persona_id_t), owners_num) ;
            if(!owners_list)
            {
                res = SSERVICE_ERROR_INSUFFICIENTMEMORY ;
                XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, error 0x%x", __FUNCTION__, res  ) ;
            }
            else
            {
                for( int i = 0; i < owners_num; i++)
                {
                    owners_list[i] = [[owners objectAtIndex:i] integerValue]  ;
                }
            }
        }
        sservice_data_handle_t dataHandle = 0 ;
        NSMutableData *data = nil ;
        if( IS_SUCCESS(res))
        {
            data = [NSMutableData dataWithData:[dataStr dataUsingEncoding:STRING_ENCODING]];
            if(!data)
            {
                XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INVALIDPOINTER.error_or_warn_code ) ;
                res = SSERVICE_ERROR_INVALIDPOINTER ;
            }
        }
        
        //Call runtime to performe action
        if( IS_SUCCESS(res))
        {
            NSData *tag = nil ;
            if(tagStr)
            {
                tag =  [tagStr dataUsingEncoding:STRING_ENCODING ];
            }
            
            sservice_secure_data_policy_t access_policy ;
            access_policy.device_policy = (sservice_locality_type_t)deviceLocality;
            access_policy.application_policy = (sservice_application_access_control_type_t)appAccessControl ;
            access_policy.sensitivity_level = sensitivityLevel ;
            access_policy.flags.no_store = noStore;
            access_policy.flags.no_read = noRead;
            res = sservice_securedata_create_from_data( (sservice_size_t)[data length],
                                                       [data bytes ],
                                                       tag ? (sservice_size_t)[tag length]:0,
                                                       tag ? [tag bytes ]:NULL,
                                                       &access_policy, extraKey,
                                                       creator ,
                                                       (sservice_size_t)owners_num,
                                                       owners_list,
                                                       authentication_token,
                                                       trustedWebDomainsStr ? [trustedWebDomainsStr cStringUsingEncoding:NSUTF8StringEncoding ] :NULL,
                                                       &dataHandle);
        }
        if(data)
        {
            [data resetBytesInRange:NSMakeRange(0, [data length]) ];
            [data setLength:0];
        }
        if(owners_list)
        {
            free(owners_list) ;
        }
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, res.error_or_warn_code ) ;
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success, Handle 0x%llx", __FUNCTION__, dataHandle ) ;
            pluginResult = [ CDVPluginResult resultWithStatus:CDVCommandStatus_OK messageAsDouble:dataHandle ];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}



/** Function is callback for cordova call of
 *	cordova.exec(success, failInternal, "IntelSecurity", "SecureDataCreateFromSealedData", [sealedData]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureDataCreateFromSealedData:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    
    if( ![self checkArguments:command argNumber:2])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return;
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        NSString *dataStr = NULL;
        NSData *data = NULL;
        sservice_data_handle_t extraKey= 0;
        //Retrieve all necessary parameters.
        dataStr = [command.arguments objectAtIndex:0];
        extraKey = [self getHandleFromArgument: command argNumber: 1 ];
        //Convert them to C compatible types
        if(dataStr)
        {
            data = [[NSData alloc]initWithBase64Encoding: dataStr];
        }
        if(!data)
        {
            res = SSERVICE_ERROR_INTEGRITYVIOLATIONERROR ;
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, error 0x%x", __FUNCTION__, res.error_or_warn_code ) ;
        }
        sservice_data_handle_t dataHandle = 0;
        CDVPluginResult *pluginResult = NULL ;
        
        //Call runtime to performe action
        if( IS_SUCCESS(res))
        {
            res = sservice_securedata_create_from_sealed_data( (sservice_size_t)[data length], [data bytes], extraKey, &dataHandle );
        }
        
        //Prepare callback parameters and execute necessary callback.
        if( IS_FAILED(res) )
        {
            XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, res.error_or_warn_code ) ;
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:res.error_or_warn_code];
        }
        else
        {
            pluginResult = [ CDVPluginResult resultWithStatus:CDVCommandStatus_OK messageAsDouble:dataHandle ];
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success, handle 0x%llx", __FUNCTION__, dataHandle ) ;
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}



/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureDataGetData", [instanceID]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureDataGetData:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    
    if( ![self checkArguments:command argNumber:1])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return;
	}
    
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_handle_t data_handle = [self getHandleFromArgument: command argNumber: 0 ];
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        sservice_size_t data_size ;
        //request data size and prepare buffer for runtime call
        res = sservice_securedata_get_size( data_handle, &data_size ) ;
        NSString *result = NULL ;
        char *data = NULL ;
        if( IS_SUCCESS(res))
        {
            data = malloc(data_size ) ;
            if( data == NULL )
            {
                res = SSERVICE_ERROR_INSUFFICIENTMEMORY ;
                XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, error 0x%x", __FUNCTION__, res.error_or_warn_code ) ;
            }
        }
        if( IS_SUCCESS(res))
        {
            //Call runtime to performe action
            res = sservice_securedata_get_data(data_handle, authentication_token, data_size, data ) ;
        }
        if( IS_SUCCESS(res))
        {
            //and prepare arguments for callback
            result = [[NSString alloc] initWithBytes:data length:data_size encoding:STRING_ENCODING];
        }
        if(data)
        {
            memset( data, 0, data_size );
            free(data);
        }
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:res.error_or_warn_code];
            XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, res.error_or_warn_code ) ;
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK messageAsString:result ];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}




/** Function is callback for cordova call of
 *		cordova.exec(success, failInternal, "IntelSecurity", "SecureDataGetSealedData", [instanceID]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */

- (void) SecureDataGetSealedData:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    
    if( ![self checkArguments:command argNumber:1])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return;
	}
 	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        //Retrieve all necessary parameters.
        sservice_handle_t data_handle = [self getHandleFromArgument: command argNumber: 0 ];
        sservice_size_t sealed_data_size = 0;
        char *sealed_data = NULL ;
        //request data size and prepare buffer for runtime call
        res = sservice_securedata_get_sealed_size( data_handle,&sealed_data_size ) ;
        if( IS_SUCCESS(res))
        {
            sealed_data = malloc( sealed_data_size) ;
            if(!sealed_data)
            {
                res = SSERVICE_ERROR_INSUFFICIENTMEMORY ;
            }
        }
        //Call runtime to performe action
        if( IS_SUCCESS(res))
        {
            res = sservice_securedata_get_sealed_data( data_handle,sealed_data_size,sealed_data );
        }
        NSString *base64Out = nil ;
        if( IS_SUCCESS(res))
        {
            NSData *temp = [[ NSData alloc ] initWithBytes:sealed_data length:sealed_data_size ];
#if __IPHONE_OS_VERSION_MAX_ALLOWED > 70000
            base64Out = [temp base64EncodedStringWithOptions:0] ;
#else
            base64Out = [temp base64EncodedString] ;
#endif
        }
        if(sealed_data)
	{
		free( sealed_data ) ;
        }
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus : CDVCommandStatus_OK messageAsString:
                            base64Out];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}

/** Function is callback for cordova call of
 *		cordova.exec(success, failInternal, "IntelSecurity", "SecureDataChangeExtraKey", [instanceID, extraKeyInstanceID]);
 * @param [in] command - instanceID for the secureData instance that the key in the extraKeyInstanceID should be changed too.
 * @return nothing; result is passed to callback using self.commandDelegate
 */

- (void) SecureDataChangeExtraKey:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    
    if( ![self checkArguments:command argNumber:2])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return;
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        //Retrieve all necessary parameters.
        sservice_handle_t instanceID = [self getHandleFromArgument: command argNumber: 0 ];
		sservice_handle_t extra_key_instanceID = [self getHandleFromArgument: command argNumber: 1 ];
        res = sservice_securedata_change_extraKey(instanceID, extra_key_instanceID);
		
		//Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus : CDVCommandStatus_OK];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}

/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureDataGetTag", [instanceID]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureDataGetTag:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    
    if( ![self checkArguments:command argNumber:1])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return;
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        //Retrieve all necessary parameters.
        sservice_handle_t data_handle = [self getHandleFromArgument: command argNumber: 0 ];
        sservice_size_t tag_size =0;
        //request data size and prepare buffer for runtime call
        res = sservice_securedata_get_tag_size(data_handle, &tag_size);
        char *tag = NULL;
        
        if( IS_SUCCESS(res))
        {
            if(tag_size > 0 )
            {
                tag = calloc( 1, tag_size) ;
                if(!tag)
                {
                    res = SSERVICE_ERROR_INSUFFICIENTMEMORY ;
                }
            }
        }
        NSString *result = @"";
        //Call runtime to performe action
        if( IS_SUCCESS(res) && tag_size > 0 )
        {
            res = sservice_securedata_get_tag(data_handle,tag_size,tag );
            if( IS_SUCCESS(res))
            {
                result = [[NSString alloc] initWithBytes:tag length:tag_size encoding:STRING_ENCODING];
            }
        }
        if(tag)
        {
            free( tag ) ;
        }
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus : CDVCommandStatus_OK messageAsString:result];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}





/** Function is callback for cordova call of
 *         ccordova.exec(success, failInternal, "IntelSecurity", "SecureDataGetPolicy", [instanceID]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureDataGetPolicy:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    
    if( ![self checkArguments:command argNumber:1])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return;
	}
    //this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        //Retrieve all necessary parameters.
        sservice_handle_t data_handle = [self getHandleFromArgument: command argNumber: 0 ];
        sservice_secure_data_policy_t access_policy ;
        memset( &access_policy, 0, sizeof(access_policy) );
        //Call runtime to performe action
        res = sservice_securedata_get_policy(data_handle, &access_policy);
        NSMutableDictionary *result = [[NSMutableDictionary alloc] init];
        [result setObject:[NSNumber numberWithInteger:access_policy.application_policy] forKey:@"appAccessControl"];
        [result setObject:[NSNumber numberWithInteger:access_policy.device_policy] forKey:@"deviceLocality"];
        [result setObject:[NSNumber numberWithInteger:access_policy.sensitivity_level] forKey:@"sensitivityLevel"];
	[result setObject:[NSNumber numberWithInteger:access_policy.flags.no_store] forKey:@"noStore"];
	[result setObject:[NSNumber numberWithInteger:access_policy.flags.no_read] forKey:@"noRead"];
        
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_OK messageAsDictionary:result];
        }
	    // Execute sendPluginResult on this plugin's commandDelegate, passing in the ...
        // ... instance of CDVPluginResult
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}



/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureDataGetOwners", [instanceID]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureDataGetOwners:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    if( ![self checkArguments:command argNumber:1])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return;
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        //Retrieve all necessary parameters.
        sservice_handle_t data_handle = [self getHandleFromArgument: command argNumber: 0 ];
        sservice_size_t number_of_owners = 0 ;
        sservice_persona_id_t* owners_buffer = NULL ;
        //request data size and prepare buffer for runtime call
        res = sservice_securedata_get_number_of_owners(data_handle, &number_of_owners );
        if( IS_SUCCESS(res) )
        {
            owners_buffer = calloc( sizeof(sservice_persona_id_t), number_of_owners ) ;
            if(!owners_buffer)
            {
                res = SSERVICE_ERROR_INSUFFICIENTMEMORY ;
            }
        }
        //Call runtime to performe action
        if( IS_SUCCESS(res) )
        {
            res = sservice_securedata_get_owners(data_handle, sizeof(sservice_persona_id_t)*number_of_owners, owners_buffer);
        }
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL;
        if( IS_SUCCESS(res) )
        {
            //Convert results to ObjectiveC NSArray to return to cordova runtime
            NSMutableArray *result = [[NSMutableArray alloc] initWithCapacity:number_of_owners];
            for( int i = 0; i < number_of_owners; i++ )
            {
                [result addObject:[NSNumber numberWithInteger:owners_buffer[i]]];
            }
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus:CDVCommandStatus_OK messageAsArray:result ];
        }
        else
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        if(owners_buffer)
        {
            free( owners_buffer) ;
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}




/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureDataGetCreator", [instanceID]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureDataGetCreator:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    
    if( ![self checkArguments:command argNumber:1])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return ;
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        //Retrieve all necessary parameters.
        sservice_handle_t data_handle = [self getHandleFromArgument: command argNumber: 0 ];
        sservice_persona_id_t creator ;
        //Prepare callback parameters and execute necessary callback.
        res = sservice_securedata_get_creator(data_handle, &creator );
        
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK messageAsDouble:creator];
        }
        // Execute sendPluginResult on this plugin's commandDelegate, passing in the ...
        // ... instance of CDVPluginResult
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}

/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureDataGetTrustedWebDomainsList", [instanceID]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureDataGetWebOwners:(CDVInvokedUrlCommand *)command
{
    if( ![self checkArguments:command argNumber:1])
    {
        XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return;
    }
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        //Retrieve all necessary parameters.
        sservice_handle_t data_handle = [self getHandleFromArgument: command argNumber: 0 ];
        sservice_size_t list_size = 0 ;
        char* list_buffer = NULL ;
        //request data size and prepare buffer for runtime call
        res = sservice_securedata_get_trusted_web_domains_list_size( data_handle, &list_size ) ;
        if( IS_SUCCESS(res) )
        {
            list_buffer = calloc( 1, list_size ) ;
            if(!list_buffer)
            {
                res = SSERVICE_ERROR_INSUFFICIENTMEMORY ;
            }
        }
        //Call runtime to performe action
        if( IS_SUCCESS(res) )
        {
            res = sservice_securedata_get_trusted_web_domains_list(data_handle, list_size, list_buffer);
        }
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL;
        if( IS_SUCCESS(res) )
        {
            //list size should be at least 3 since the empty array is "[]" + the null terminator
            if(list_size>strlen("[]"))
            {
                //Convert results to ObjectiveC NSArray to return to cordova runtime. Size-1 since the encoding adds the \0 automatically
                NSString *result = [[NSString alloc] initWithBytes:list_buffer length:list_size-1 encoding:NSUTF8StringEncoding];
           	 XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
           	 pluginResult = [ CDVPluginResult resultWithStatus:CDVCommandStatus_OK messageAsString:result ];
            }
            else
	    {
                pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                    messageAsInt:SSERVICE_ERRORCODE_INTERNAL_ERROR];
            }
        }
        else
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        if(list_buffer)
        {
            free( list_buffer) ;
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}


/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureDataDestroy", [instanceID]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureDataDestroy:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    
    if( ![self checkArguments:command argNumber:1])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return ;
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        //Retrieve all necessary parameters.
        sservice_handle_t data_handle = [self getHandleFromArgument: command argNumber: 0 ];
        //Prepare callback parameters and execute necessary callback.
        res = sservice_securedata_destroy( data_handle ) ;
        
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK ];
        }
        // Execute sendPluginResult on this plugin's commandDelegate, passing in the ...
        // ... instance of CDVPluginResult
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}


/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureStorageRead", [defaults.id, defaults.storageType]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureStorageRead:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    
    if( ![self checkArguments:command argNumber:3])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return ;
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        NSString *storageId = [command.arguments objectAtIndex:0];
        //Retrieve all necessary parameters.
        NSInteger storageType = [self getIntFromArgument: command argNumber: 1 ];
        sservice_data_handle_t extraKey = [self getHandleFromArgument: command argNumber: 2 ];
        sservice_data_handle_t data_handle ;
        //Prepare callback parameters and execute necessary callback.
        res = sservice_securestorage_read([storageId UTF8String],
                                          (sservice_secure_storage_type_t)storageType, extraKey, &data_handle );
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus    : CDVCommandStatus_OK
                                                  messageAsDouble:data_handle
                            ];
        }
        // Execute sendPluginResult on this plugin's commandDelegate, passing in the ...
        // ... instance of CDVPluginResult
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}




/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureStorageWrite", [defaults.id, defaults.storageType, defaults.instanceID]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureStorageWrite:(CDVInvokedUrlCommand *)command
{
    if( ![self checkArguments:command argNumber:3])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        //Retrieve all necessary parameters.
        NSString *storageId = [command.arguments objectAtIndex:0];
        NSInteger storageType = [self getIntFromArgument: command argNumber: 1 ];
        sservice_handle_t data_handle = [self getHandleFromArgument: command argNumber: 2 ];
        XSSLOG_BRIDGE(LOG_INFO, "Entering to %s, storageID: %p, type: %d, data %d", __FUNCTION__, storageId, storageType, data_handle ) ;
        
        //Prepare callback parameters and execute necessary callback.
        res = sservice_securestorage_write(
                                        [storageId UTF8String],
                                        (sservice_secure_storage_type_t)storageType,
                                        data_handle ) ;
        
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK ];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}


/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureStorageDelete", [defaults.id, defaults.storageType]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureStorageDelete:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    
    if( ![self checkArguments:command argNumber:2])
	{
	XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return ;
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        //Retrieve all necessary parameters.
        NSString *storageId = [command.arguments objectAtIndex:0];
        NSInteger storageType = [self getIntFromArgument: command argNumber: 1 ];
        XSSLOG_BRIDGE(LOG_INFO, "Entering to %s, storageID: %p, type: %d", __FUNCTION__, storageId, storageType ) ;
        //Prepare callback parameters and execute necessary callback.
        res = sservice_securestorage_delete(
                                            [storageId UTF8String],
                                            (sservice_secure_storage_type_t)storageType	);
        
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK ];
        }
        // Execute sendPluginResult on this plugin's commandDelegate, passing in the ...
        // ... instance of CDVPluginResult
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}


/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureStorageDelete", [defaults.id, defaults.storageType]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
 - (void) SecureTransportOpen:(CDVInvokedUrlCommand *)command
{
    if( ![self checkArguments:command argNumber:4])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        NSString *url = [command.arguments objectAtIndex:0];
        NSInteger method = [self getIntFromArgument: command argNumber: 1];
        NSString *server = [command.arguments objectAtIndex:2];
        NSInteger timeout = [self getIntFromArgument: command argNumber: 3 ];
        XSSLOG_BRIDGE(LOG_INFO, "Entering to %s, url: %s, method: %i, server: %s, timeout:%i", __FUNCTION__, [url UTF8String], method, [server UTF8String], timeout ) ;
        sservice_transport_handle_t handle = 0 ;
        res = sservice_securetransport_open([ url UTF8String ],
                            [ server  UTF8String ],  
                            NULL,//client_server_private_certificate
                            (sservice_size_t)timeout, 
                            (sservice_http_method_t)method, 
                            (sservice_transport_handle_t*)&handle);

        
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus:CDVCommandStatus_OK messageAsDouble:handle ];
         }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}

/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureStorageDelete", [defaults.id, defaults.storageType]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureTransportSetURL:(CDVInvokedUrlCommand *)command
{
    if( ![self checkArguments:command argNumber:3])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;

        sservice_transport_handle_t transportInstanceID= [self getHandleFromArgument: command argNumber: 0 ] ;
        NSString *url = [command.arguments objectAtIndex:1];
	NSString *server = [command.arguments objectAtIndex:2];
        //Retrieve all necessary parameters.
        XSSLOG_BRIDGE(LOG_INFO, "Entering to %s, url: %s, PublicKey: %s" , __FUNCTION__, [url UTF8String],[server  UTF8String]) ;
        res = sservice_securetransport_set_url( transportInstanceID, [url UTF8String],[server  UTF8String]);

        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK ];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}

/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureStorageDelete", [defaults.id, defaults.storageType]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureTransportSetMethod:(CDVInvokedUrlCommand *)command
{
    if( ![self checkArguments:command argNumber:2])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        sservice_transport_handle_t transportInstanceID= [self getHandleFromArgument: command argNumber: 0 ] ;
        NSInteger method = [self getIntFromArgument: command argNumber: 1];
        XSSLOG_BRIDGE(LOG_INFO, "Entering to %s, method: %i" , __FUNCTION__, method) ;
        res = sservice_securetransport_set_method( transportInstanceID, (sservice_http_method_t)method);    
        
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK ];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}

/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureStorageDelete", [defaults.id, defaults.storageType]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
 - (void) SecureTransportSetHeaders:(CDVInvokedUrlCommand *)command
{
    if( ![self checkArguments:command argNumber:2])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;

        sservice_transport_handle_t transportInstanceID= [self getHandleFromArgument: command argNumber: 0 ] ;
        NSString *headers = [command.arguments objectAtIndex:1];
        //Retrieve all necessary parameters.
        XSSLOG_BRIDGE(LOG_INFO, "Entering to %s, headers: %s" , __FUNCTION__, [headers UTF8String]) ;
        res = sservice_securetransport_set_headers(transportInstanceID, [headers UTF8String]);    

        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK ];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}

/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureStorageDelete", [defaults.id, defaults.storageType]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureTransportSendRequest:(CDVInvokedUrlCommand *)command
{
    if( ![self checkArguments:command argNumber:4])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;

        sservice_transport_handle_t transportInstanceID= [self getHandleFromArgument: command argNumber: 0 ] ;
        NSString *requestBody = [command.arguments objectAtIndex:1];
        NSInteger requestFormat = [self getIntFromArgument: command argNumber: 2];
        NSString *secureDataDescriptors = [command.arguments objectAtIndex:3];
        XSSLOG_BRIDGE(LOG_INFO, "Entering to %s, requestBody: %s, format: %i, secureDataDescriptors: %s" , __FUNCTION__, [requestBody UTF8String],requestFormat, [secureDataDescriptors UTF8String]) ;
        sservice_size_t response_header_size = 0 ;
        sservice_size_t response_body_size = 0 ;
	unsigned long http_status_code = 0 ;
        res = sservice_securetransport_send_request(transportInstanceID,
                    [requestBody UTF8String],
                    (sservice_secure_transport_content_type_t)requestFormat, 
                    [secureDataDescriptors UTF8String] ,
		    &http_status_code,
                    &response_header_size, &response_body_size );
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        
        char *response_body = NULL ;
        char *response_header = NULL;
        
        //setting the headers
        if( IS_SUCCESS( res ) && response_header_size>0)
        {
            response_header = malloc(response_header_size) ;
            if(!response_header)
            {
                res = SSERVICE_ERROR_INSUFFICIENTMEMORY ;
                XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, error 0x%x", __FUNCTION__, res.error_or_warn_code ) ;
            }
            if( IS_SUCCESS( res ))
            {
                res = sservice_securetransport_get_response_header(transportInstanceID,
                                                                   response_header_size,
                                                                   response_header );
            }
        }
        
        //setting the response body
        if( IS_SUCCESS( res ) && response_body_size>0)
        {
            response_body =malloc(response_body_size) ;
            if(!response_body)
            {
                res = SSERVICE_ERROR_INSUFFICIENTMEMORY ;
                XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, error 0x%x", __FUNCTION__, res.error_or_warn_code ) ;
            }
            if( IS_SUCCESS( res ))
            {
                res = sservice_securetransport_get_response_body(transportInstanceID,
                                                                 response_body_size,
                                                                 response_body);
            }

        }
        
        //setting the cordova response
        if( IS_FAILED(res) )
        {
            
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            NSMutableDictionary *result = [[NSMutableDictionary alloc] init];
	    //sets the http status code from the http request
	    [result setObject:[NSString stringWithFormat:@"%d",http_status_code] forKey:@"responseHttpStatus"];
	    if(!response_header)
            {
                [result setObject:[NSString stringWithUTF8String:""] forKey:@"responseHeader"];
            }
            else
            {
                [result setObject:[NSString stringWithUTF8String:response_header] forKey:@"responseHeader"];
            }
	    
	    if(!response_body)
            {
               [result setObject:[NSString stringWithUTF8String:""] forKey:@"responseBody"];
            }
            else
            {
                [result setObject:[NSString stringWithUTF8String:response_body] forKey:@"responseBody"];
            }

            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_OK messageAsDictionary:result];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        if(response_body)
        {
            free(response_body) ;
        }
        if(response_header)
        {
            free(response_header) ;
        }
        
    }];
}


/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureStorageAbort", [defaults.id, defaults.storageType]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureTransportAbort:(CDVInvokedUrlCommand *)command
{
    if( ![self checkArguments:command argNumber:1])
    {
        XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }
    //this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        sservice_transport_handle_t transportInstanceID= [self getHandleFromArgument: command argNumber: 0 ] ;
        XSSLOG_BRIDGE(LOG_INFO, "Entering to %s, transportInstanceID: %x" , __FUNCTION__, transportInstanceID) ;
        
        res = sservice_securetransport_abort(transportInstanceID);
        
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK ];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}



/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "SecureStorageDelete", [defaults.id, defaults.storageType]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) SecureTransportDestroy:(CDVInvokedUrlCommand *)command
{
    if( ![self checkArguments:command argNumber:1])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt:SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
	}
	//this call will move the rest of the procedure to another thread
    [self.commandDelegate runInBackground:^{
        sservice_result_t res = SSERVICE_SUCCESS_NOINFO ;
        sservice_transport_handle_t transportInstanceID= [self getHandleFromArgument: command argNumber: 0 ] ;
        XSSLOG_BRIDGE(LOG_INFO, "Entering to %s, transportInstanceID: %x" , __FUNCTION__, transportInstanceID) ;

        res = sservice_securetransport_destroy(transportInstanceID);
        
        //Prepare callback parameters and execute necessary callback.
        CDVPluginResult *pluginResult = NULL ;
        if( IS_FAILED(res) )
        {
            pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR
                                                messageAsInt:res.error_or_warn_code];
        }
        else
        {
            XSSLOG_BRIDGE(LOG_INFO, "Exiting from %s, Success", __FUNCTION__ ) ;
            pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK ];
        }
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
    }];
}





#ifndef NDEBUG
/** Function is callback for cordova call of
 *         cordova.exec(success, failInternal, "IntelSecurity", "??????", [log_level]);
 * @param [in] command - array of parameters, passed by Cordova runtime.
 * @return nothing; result is passed to callback using self.commandDelegate
 */
- (void) log:(CDVInvokedUrlCommand *)command
{
	//Check input parameter. Probably not necessary
    if( ![self checkArguments:command argNumber:1])
	{
		XSSLOG_BRIDGE(LOG_ERROR, "Exiting from %s, error 0x%x", __FUNCTION__, SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code ) ;
        CDVPluginResult *pluginResult = [CDVPluginResult resultWithStatus:CDVCommandStatus_ERROR messageAsInt: SSERVICE_ERROR_INTERNAL_ERROR.error_or_warn_code];
        [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
        return ;
	}
	//Retrieve all necessary parameters.
	NSString *log = [command.arguments objectAtIndex:0] ;
	//Prepare callback parameters and execute necessary callback.
	CDVPluginResult *pluginResult = NULL ;
    
	//Prepare callback parameters and execute necessary callback.
	sservice_log( LOG_SOURCE_JS, LOG_INFO, "%s", [log cStringUsingEncoding:[NSString defaultCStringEncoding]]) ;
    pluginResult = [ CDVPluginResult resultWithStatus: CDVCommandStatus_OK ];
    [self.commandDelegate sendPluginResult:pluginResult callbackId:command.callbackId];
}

#endif
@end






