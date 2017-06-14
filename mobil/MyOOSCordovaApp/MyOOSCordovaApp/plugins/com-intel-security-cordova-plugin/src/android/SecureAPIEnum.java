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

package com.intel.security;

public enum SecureAPIEnum {
	//Init
    GLOBAL_INIT("GlobalInit"),
    // Secure Data
    SECURE_DATA_CREATE_FROM_DATA("SecureDataCreateFromData"),
    SECURE_DATA_CREATE_FROM_SEALED_DATA("SecureDataCreateFromSealedData"),
    SECURE_DATA_CHANGE_EXTRA_KEY("SecureDataChangeExtraKey"),
    SECURE_DATA_GET_DATA_STRING("SecureDataGetData"),
    SECURE_DATA_GET_SEALED_DATA_STRING("SecureDataGetSealedData"),
    SECURE_DATA_GET_TAG_STRING("SecureDataGetTag"),
    SECURE_DATA_GET_POLICY_STRING("SecureDataGetPolicy"),
    SECURE_DATA_GET_OWNERS_STRING("SecureDataGetOwners"),
    SECURE_DATA_GET_CREATOR_STRING("SecureDataGetCreator"),    
    SECURE_DATA_GET_WEB_OWNERS_STRING("SecureDataGetWebOwners"),
    SECURE_DATA_DESTROY_STRING("SecureDataDestroy"),
    // Secure Storage
    SECURE_STORAGE_READ_STRING("SecureStorageRead"),
    SECURE_STORAGE_WRITE_STRING("SecureStorageWrite"),
    SECURE_STORAGE_DELETE_STRING("SecureStorageDelete"),
    // Secure Transport
    SECURE_TRANSPORT_OPEN_STRING("SecureTransportOpen"),
    SECURE_TRANSPORT_SET_URL_STRING("SecureTransportSetURL"),
    SECURE_TRANSPORT_SET_METHOD_STRING("SecureTransportSetMethod"),
    SECURE_TRANSPORT_SET_HEADERS_STRING("SecureTransportSetHeaders"),
    SECURE_TRANSPORT_SEND_REQUEST_STRING("SecureTransportSendRequest"),
	SECURE_TRANSPORT_ABORT_STRING("SecureTransportAbort"),
    SECURE_TRANSPORT_DESTROY_STRING("SecureTransportDestroy");
    
    
    private String functionName;
    private SecureAPIEnum(String functionName) {
        this.functionName = functionName;
    }
    public String GetValue(){
        return this.functionName;
    }
    
    static public boolean IsGlobalInitAPI(String functionName)throws ErrorCodeException{
        
        SecureAPIEnum api = SecureAPIEnum.CreateSecureAPIEnum(functionName); 
        return api.GetValue().equals(SecureAPIEnum.GLOBAL_INIT.GetValue());
    }
    
    /*
    static public boolean IsSupportedAPI(String functionName){
        
        for (SecureAPIEnum api : SecureAPIEnum.values()) {
            if (api.GetValue().equals(functionName)) {
                return true;
            }
        }
        
        // did not match any API
        return false;        
    }
    */
    
    static public SecureAPIEnum CreateSecureAPIEnum(String functionName) throws ErrorCodeException{
        
        for (SecureAPIEnum api : SecureAPIEnum.values()) {
            if (api.GetValue().equals(functionName)) {
                return api;
            }
        }
        
        // did not match any API
        throw new ErrorCodeException(ErrorCodeEnum.INTERNAL_ERROR_OCCURRED.getValue());         
    }
    
};
