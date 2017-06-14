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

import java.io.UnsupportedEncodingException;

import org.json.JSONArray;
import org.json.JSONException;

public class SecureStorage {

    protected native int readJNI(String id, int storageType, long extraKey, int instanceIDArraySize, /*OUT*/ long[] instanceIDArray);
	protected native int writeJNI(String id, int storageType, long instanceID);    
    protected native int deleteJNI(String id, int storageType);
   
    final protected String dataEncoding = "UTF-8";
    
    public long ReadAPI(String id, int storageType, long extraKey) throws ErrorCodeException {

        // create array to get instanceID as a result in array 
        final int instanceIDArraySize = 1; 
        long[] instanceIDArray = new long[instanceIDArraySize];
        
        int result = readJNI(id, storageType, extraKey, instanceIDArraySize, instanceIDArray);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
                       
        long instanceID = instanceIDArray[0];        
        return instanceID;
    }
    
    public void WriteAPI(String id, int storageType, long dataHandle) throws ErrorCodeException {

        int result = writeJNI(id, storageType, dataHandle);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        
        return;
    }
	    
    public void DeleteAPI(String id, int storageType) throws ErrorCodeException {

        int result = deleteJNI(id, storageType);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        
        return;
    }
}
