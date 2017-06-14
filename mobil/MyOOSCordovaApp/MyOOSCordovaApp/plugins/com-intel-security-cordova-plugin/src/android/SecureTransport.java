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
import org.json.JSONException;
import org.json.JSONObject;

public class SecureTransport {

	protected native int openJNI(String url, int method, String serverKey,
			int timeout, int instanceIDArraySize, long[] instanceIDArray);
	protected native int setURLJNI(long instanceID, String url,String serverKey);
	protected native int setMethodJNI(long instanceID, int method);
	protected native int setHeadersJNI(long instanceID, String headers);
	protected native int getResponseHeaderJNI(long instanceID, int responseHeaderSize,
			byte[] responseHeaderBuffer);
	protected native int getResponseBodyJNI(long instanceID, int responseBodySize,
			byte[] responseBodyBuffer);
	protected native int sendRequestJNI(long instanceID, String requestBody,
			int requestFormat, String secureDataDescriptors,
			long[] httpArray, int answerArraySize, int[] answerArray);
	protected native int abortJNI(long instanceID);
	protected native int destroyJNI(long instanceID);

    final protected String dataEncoding = "UTF-16LE";
    final protected String webStringEncoding = "UTF-8";

	public long OpenAPI(String url, int method, String serverKey,
			int timeout) throws ErrorCodeException {

		// create array to get instanceID as a result in array 
		final int instanceIDArraySize = 1; 
		long[] instanceIDArray = new long[instanceIDArraySize];
		
		int result = openJNI(url, method, serverKey, timeout, 
				instanceIDArraySize, instanceIDArray);
		if (result != 0) {            
			throw new ErrorCodeException(result);
		}

		long instanceID = instanceIDArray[0];        
		return instanceID;    
	}

	public void SetURLAPI(long instanceID, String url,String serverKey) throws ErrorCodeException {

		int result = setURLJNI(instanceID, url,serverKey);
		if (result != 0) {            
			throw new ErrorCodeException(result);
		}

		return;        
	}

	public void SetMethodAPI(long instanceID, int method) throws ErrorCodeException {

		int result = setMethodJNI(instanceID, method);
		if (result != 0) {            
			throw new ErrorCodeException(result);
		}

		return;        
	}
	public void SetHeadersAPI(long instanceID, String headers) throws ErrorCodeException {

		int result = setHeadersJNI(instanceID, headers);
		if (result != 0) {            
			throw new ErrorCodeException(result);
		}

		return;
	}


	public JSONObject SendRequestAPI(long instanceID, String requestBody, int requestFormat, 
			String secureDataDescriptors) throws ErrorCodeException, UnsupportedEncodingException, JSONException {

		// create array to get dataSize as a result in array 
		final int answerArraySize = 2; // [responseHeaderSize, responseBodySize]
		int[] answerArray = new int[answerArraySize];
		long[] httpArray=new long[1];

		int result = sendRequestJNI(instanceID, requestBody, requestFormat, secureDataDescriptors, 
				httpArray,answerArraySize, answerArray);
		if (result != 0) {            
			throw new ErrorCodeException(result);
		}

		String responseHeader = "";

		int responseHeaderSize = answerArray[0];
		if (responseHeaderSize > 0){
			byte[] responseHeaderBuffer = new byte[responseHeaderSize];
			result = getResponseHeaderJNI(instanceID, responseHeaderSize, responseHeaderBuffer);
			if (result != 0) {        
				throw new ErrorCodeException(result);
			}

			responseHeader = new String(responseHeaderBuffer,0,responseHeaderSize-1,webStringEncoding);
		}
		String responseBody = "";
		int responseBodySize = answerArray[1];
		if (responseBodySize > 0){
			byte[] responseBodyBuffer = new byte[responseBodySize];
			result = getResponseBodyJNI(instanceID, responseBodySize, responseBodyBuffer);
			if (result != 0) {        
				throw new ErrorCodeException(result);
			}

			responseBody = new String(responseBodyBuffer,0,responseBodySize-1,webStringEncoding);
		}

		// write response to JSONObject
        JSONObject responseObject = new JSONObject();                   
        responseObject.put("responseHeader", responseHeader);
        responseObject.put("responseBody", responseBody);		
		responseObject.put("responseHttpStatus", httpArray[0]);
		
		return responseObject;
	}

	public void AbortAPI(long instanceID) throws ErrorCodeException {

		int result = abortJNI(instanceID);
		if (result != 0) {            
			throw new ErrorCodeException(result);
		}

		return;        
	} 
	
	public void DestroyAPI(long instanceID) throws ErrorCodeException {

		int result = destroyJNI(instanceID);
		if (result != 0) {            
			throw new ErrorCodeException(result);
		}

		return;        
	}    

}
