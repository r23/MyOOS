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
import org.json.JSONObject;

import android.util.Base64;

public class SecureData {

    protected native int createFromDataJNI(int dataSize, byte[] data, int tagSize, byte[] tag, long extraKey,
            int appAccessControl, int deviceLocality, int sensitivityLevel, int noStore, int noRead,long creatorUID, int numberOfOwners, 
            long[] owners, long authenticationToken,  String trustedWebDomains, int instanceIDArraySize, /*OUT*/ long[] instanceIDArray);    
    protected native int createFromSealedDataJNI(int sealedDataSize, byte[] sealedData, long extraKey, int instanceIDArraySize, /*OUT*/ long[] instanceIDArray);    
    protected native int getDataSizeJNI(long instanceID, int answerArraySize, /*OUT*/ int[] answerArray);
    protected native int getDataJNI(long instanceID, long authenticationToken, int bufferSize, /*OUT*/ byte[] buffer);
    protected native int getSealedDataSizeJNI(long instanceID, int answerArraySize, /*OUT*/ int[] answerArray);
    protected native int getSealedDataJNI(long instanceID, int bufferSize, /*OUT*/ byte[] buffer);
    protected native int changeExtraKeyJNI(long instanceID, long extraKeyInstanceID);    
    protected native int getTagSizeJNI(long instanceID, int answerArraySize, /*OUT*/ int[] answerArray);
    protected native int getTagJNI(long instanceID, int bufferSize, /*OUT*/ byte[] buffer);
    protected native int getPolicyJNI(long instanceID, int answerArraySize, /*OUT*/ int[] answerArray);
    protected native int getCreatorJNI(long instanceID, int answerArraySize, /*OUT*/ long[] answerArray);
    protected native int getNumberOfOwnersJNI(long instanceID, int answerArraySize, /*OUT*/ int[] answerArray);
    protected native int getOwnersJNI(long instanceID, int ownersArraySize, /*OUT*/ long[] ownersArray);  
    protected native int getTrustedWebDomainsJNI(long instanceID, StringBuffer trustedWebDomains);
    
    protected native int destroyJNI(long instanceID);

    
    final protected String dataEncoding = "UTF-8";

    public long CreateFromDataAPI(String dataStr, String tagStr, long extraKey, int appAccessControl, int deviceLocality,
            int sensitivityLevel, int noStore, int noRead, long creator, JSONArray ownersUIDJSONArray, String trustedWebDomains) 
            		throws ErrorCodeException, JSONException, UnsupportedEncodingException {

        if (dataStr == null         ||            
            tagStr == null          ||
            ownersUIDJSONArray.length() == 0) {            
            throw new ErrorCodeException(ErrorCodeEnum.INTERNAL_ERROR_OCCURRED.getValue());
        }

        // convert data to byte[]
        byte[] data = dataStr.getBytes(dataEncoding);

        // convert tag to byte[]        
        byte[] tag = tagStr.getBytes(dataEncoding);

        // convert ownersUIDJSONArray to long[]
        long[] owners = new long[ownersUIDJSONArray.length()];
        for (int i = 0; i<ownersUIDJSONArray.length(); i++ )
        {
            owners[i] = (long)ownersUIDJSONArray.getLong(i);
        }
         
        // create array to get instanceID as a result in array 
        final int instanceIDArraySize = 1; 
        long[] instanceIDArray = new long[instanceIDArraySize];
 
        final long authenticationToken = 0; //place holder for next version
        int result = createFromDataJNI(data.length, data, tag.length, tag, extraKey, 
                appAccessControl, deviceLocality, sensitivityLevel, noStore, noRead, creator, ownersUIDJSONArray.length(), owners, 
                authenticationToken, trustedWebDomains, instanceIDArraySize, instanceIDArray);        
        
        // clean plain text from memory
        for (int i = 0; i<data.length; i++ )
        {
            data[i] = 0;
        }
        dataStr = null;// hint for the garbage collector
        
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        
        long instanceID = instanceIDArray[0];        
        return instanceID;
    }
    
    public long CreateFromSealedDataAPI(String sealedDataBase64Str, long extraKey) throws ErrorCodeException {
        
        if (sealedDataBase64Str == null) {
            throw new ErrorCodeException(ErrorCodeEnum.INTERNAL_ERROR_OCCURRED.getValue());
        }
                
        // decode data from Base64 to byte[]
        byte[] sealedData=null;
        try {
            sealedData = Base64.decode(sealedDataBase64Str, Base64.NO_WRAP);          
        } catch (IllegalArgumentException e)
        {      
            throw new ErrorCodeException(ErrorCodeEnum.DATA_INTEGRITY_VIOLATION_DETECTED.getValue());
        }
        // create array to get instanceID as a result in array 
        final int instanceIDArraySize = 1; 
        long[] instanceIDArray = new long[instanceIDArraySize];
        
        int result = createFromSealedDataJNI(sealedData.length, sealedData, extraKey, instanceIDArraySize, instanceIDArray);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }

        long instanceID = instanceIDArray[0];        
        return instanceID;
    }
    
    public void ChangeExtraKeyAPI(long instanceID, long extraKeyInstanceID) throws ErrorCodeException {
    	
    	int result = changeExtraKeyJNI(instanceID, extraKeyInstanceID);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        return;
    }
    protected int GetDataSizeAPI(long instanceID) throws ErrorCodeException {

        // create array to get instanceID as a result in array 
        final int answerArraySize = 1; 
        int[] answerArray = new int[answerArraySize];
        
        int result = getDataSizeJNI(instanceID, answerArraySize, answerArray);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }   
        
        int dataSize = answerArray[0];
        return dataSize;
    }
    
    public String GetDataAPI(long instanceID) throws ErrorCodeException, UnsupportedEncodingException {
                
        int bufferSize = GetDataSizeAPI(instanceID);
        if (bufferSize <= 0){
            // expected to fail before
            throw new ErrorCodeException(ErrorCodeEnum.INTERNAL_ERROR_OCCURRED.getValue());
        }
        byte[] buffer = new byte[bufferSize];
        
        final long authenticationToken = 0; //place holder for next version
        int result = getDataJNI(instanceID, authenticationToken, bufferSize, buffer);
        
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
               
        String data = new String(buffer, dataEncoding);
        return data;
    }
    
    public JSONObject GetDataPolicyAPI(long instanceID) throws ErrorCodeException, JSONException {
        
        final int bufferSize = 5;
        int[] buffer = new int[bufferSize]; 
        
        int result = getPolicyJNI(instanceID, bufferSize, buffer);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        
        // write policy to JSONObject
        JSONObject policy = new JSONObject();                   
        policy.put("appAccessControl", buffer[0]);
        policy.put("deviceLocality", buffer[1]);
        policy.put("sensitivityLevel", buffer[2]); 
		policy.put("noStore", buffer[3]);
		policy.put("noRead", buffer[4]);
        return policy;
    }
    
    protected int GetTagSizeAPI(long instanceID) throws ErrorCodeException {
        
        final int answerArraySize = 1;
        int[] answerArray = new int[answerArraySize];
        
        int result = getTagSizeJNI(instanceID, answerArraySize, answerArray);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        
        int tagSize = answerArray[0];
        return tagSize;
    }
    
    public String GetTagAPI(long instanceID) throws ErrorCodeException, UnsupportedEncodingException {
		
        String tag = "";
        int bufferSize = GetTagSizeAPI(instanceID);                
        if (bufferSize > 0){
            byte[] buffer = new byte[bufferSize];
			int result = getTagJNI(instanceID, bufferSize, buffer);
	        if (result != 0) {            
	            throw new ErrorCodeException(result);
	        }           

			tag = new String(buffer, dataEncoding);
        }
        return tag;        
    }
    
    protected int GetSealedDataSizeAPI(long instanceID) throws ErrorCodeException {
        
        // create array to get dataSize as a result in array 
        final int answerArraySize = 1;
        int[] answerArray = new int[answerArraySize];
        
        int result = getSealedDataSizeJNI(instanceID, answerArraySize, answerArray);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        
        int sealedDataSize = answerArray[0];
        return sealedDataSize;
    }
    
    public String GetSealedDataAPI(long instanceID) throws ErrorCodeException {
        
        int bufferSize = GetSealedDataSizeAPI(instanceID);        
        if (bufferSize <= 0){
            // expected to fail before
            throw new ErrorCodeException(ErrorCodeEnum.INTERNAL_ERROR_OCCURRED.getValue());
        }        
        byte[] buffer = new byte[bufferSize];
        
        int result = getSealedDataJNI(instanceID, bufferSize, buffer);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        
        // encode sealed data using Base64
        String sealedDataBase64 = Base64.encodeToString(buffer, Base64.NO_WRAP);        
        return sealedDataBase64;
    }
    
    protected int GetNumberOfOwnersAPI(long instanceID) throws ErrorCodeException {
        
        // create array to get the number of owners as a result in array
        final int answerArraySize = 1;
        int[] answerArray = new int[answerArraySize];
        
        int result = getNumberOfOwnersJNI(instanceID, answerArraySize, answerArray);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        
        int numberOfOwners = answerArray[0];
        return numberOfOwners;
    }
    
    public JSONArray GetOwnersAPI(long instanceID) throws ErrorCodeException {
        
        int numberOfOwners = GetNumberOfOwnersAPI(instanceID);
        if (numberOfOwners <= 0){
            // expected to fail before
            throw new ErrorCodeException(ErrorCodeEnum.INTERNAL_ERROR_OCCURRED.getValue());
        } 
        long[] buffer = new long[numberOfOwners];
        
        int result = getOwnersJNI(instanceID, numberOfOwners, buffer);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        
        // create JSON Array with the result 
        JSONArray owners = new JSONArray();
        for (int i=0; i<numberOfOwners; i++ )
        {
            owners.put(buffer[i]);
        }

        return owners;
    }
    
    public long GetCreatorAPI(long instanceID) throws ErrorCodeException {
        
        // create array to get the creator as a result in array
        int answerArraySize = 1;
        long[] answerArray = new long[answerArraySize];
        
        int result = getCreatorJNI(instanceID, answerArraySize, answerArray);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        
        long creator = answerArray[0];
        return creator;
    }
    public String GetTrustedWebDomainsAPI(long instanceID) throws ErrorCodeException {
        
        StringBuffer wdString = new StringBuffer ("");
		int result = getTrustedWebDomainsJNI(instanceID, wdString );
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        return wdString.toString() ;
		
    }

    public void DestoryAPI(long instanceID) throws ErrorCodeException {
        
        int result = destroyJNI(instanceID);
        if (result != 0) {            
            throw new ErrorCodeException(result);
        }
        
        return;
    }
}
