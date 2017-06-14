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

#import <Cordova/CDV.h>
#import "xss_types.h"


@interface sService : CDVPlugin
{
	sservice_authentication_token_t authentication_token ;
};


- (void) GlobalInit:(CDVInvokedUrlCommand *)command;
- (void) SecureDataCreateFromData:(CDVInvokedUrlCommand *)command;
- (void) SecureDataCreateFromSealedData:(CDVInvokedUrlCommand *)command;
- (void) SecureDataGetData:(CDVInvokedUrlCommand *)command;
- (void) SecureDataGetSealedData:(CDVInvokedUrlCommand *)command;
- (void) SecureDataChangeExtraKey:(CDVInvokedUrlCommand *)command;
- (void) SecureDataGetTag:(CDVInvokedUrlCommand *)command;
- (void) SecureDataGetPolicy:(CDVInvokedUrlCommand *)command;
- (void) SecureDataGetOwners:(CDVInvokedUrlCommand *)command;
- (void) SecureDataGetCreator:(CDVInvokedUrlCommand *)command;
- (void) SecureDataGetWebOwners:(CDVInvokedUrlCommand *)command;
- (void) SecureDataDestroy:(CDVInvokedUrlCommand *)command;
- (void) SecureStorageRead:(CDVInvokedUrlCommand *)command;
- (void) SecureStorageWrite:(CDVInvokedUrlCommand *)command;
- (void) SecureStorageDelete:(CDVInvokedUrlCommand *)command;
- (void) SecureTransportOpen:(CDVInvokedUrlCommand *)command;
- (void) SecureTransportSetURL:(CDVInvokedUrlCommand *)command;
- (void) SecureTransportSetMethod:(CDVInvokedUrlCommand *)command;
- (void) SecureTransportSetHeaders:(CDVInvokedUrlCommand *)command;
- (void) SecureTransportSendRequest:(CDVInvokedUrlCommand *)command;
- (void) SecureTransportAbort:(CDVInvokedUrlCommand *)command;
- (void) SecureTransportDestroy:(CDVInvokedUrlCommand *)command;




@end

