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

var ErrorCodes =
{
    'Memory allocation failure': 2,
    'Data integrity violation detected': 8,
    'Internal error occurred': 1000
};


//Global class
var global = {
    GlobalInit: function (success, fail, optionsArray){
        try {
            var returnCode = IntelSecurityServicesWRC.GlobalWRC.globalInitStartWRC();
            if (returnCode == 0) {
                var nativeObject = new IntelSecurityServicesWRC.GlobalWRC();
                nativeObject.globalInitEndWRC()
                    .then(function (jsonResponse) {
                        try {                          
                            var jsonResponseObject = JSON.parse(jsonResponse);
                            if (jsonResponseObject.code === 0) {
                                success();
                            }
                            else {
                                fail(jsonResponseObject.code);
                            }
                        } catch (e) {
                            fail(ErrorCodes["Internal error occurred"]);
                        }
                    }, function (err) {
                        //error
                        fail(ErrorCodes["Internal error occurred"]);
                    }, function (prog) {
                        //shouldn't be called
                        fail(ErrorCodes["Internal error occurred"]);
                    });                
            }
            else {
                fail(returnCode);
            }
        }
        catch (e) {
            fail(ErrorCodes["Internal error occurred"]);
        }
    }
};
//SecureData class		
var secureData = {    
    SecureDataCreateFromData: function (success, fail, optionsArray) {
        try {

            //case the optionsArray is not an array or its length does not equal to 7
            if ((optionsArray instanceof Array) && (optionsArray.length == 11)) {
                var data = optionsArray[0];
                var tag = optionsArray[1];
                var extraKey = optionsArray[2];
                var appAccessControl = optionsArray[3];
                var deviceLocality = optionsArray[4];
                var sensitivityLevel = optionsArray[5];
                var noStore = optionsArray[6];
                var noRead = optionsArray[7];
                var creator = optionsArray[8];
                var owners = optionsArray[9];
                var webDomains = optionsArray[10];
             
                var nativeObject = new IntelSecurityServicesWRC.SecureDataWRC();
                nativeObject.secureDataCreateFromDataWRC(data, tag, extraKey,
                        appAccessControl, deviceLocality, sensitivityLevel, creator, owners, 0, noStore, noRead, webDomains)
                  .then(function (jsonResponse) {
                      try {
                          data = null;        //hint to the GC
			  var jsonResponseObject = JSON.parse(jsonResponse);
                          if (jsonResponseObject.code === 0) {
                              success(jsonResponseObject.data_handle);
                          }
                          else {
                              fail(jsonResponseObject.code);
                          }
                      }
                      catch (e) {
                          data = null;        //hint to the GC
			  fail(ErrorCodes["Internal error occurred"]);
                      }
                  }, function (err) {
                      //error
                      fail(ErrorCodes["Internal error occurred"]);
                  }, function (prog) {
                      //shouldn't be called
                      fail(ErrorCodes["Internal error occurred"]);
                  });
            }
            else {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        catch (e) {
            fail(ErrorCodes["Internal error occurred"]);
        }
    },

    SecureDataCreateFromSealedData: function (success, fail, optionsArray) {

        try {
            //case the optionsArray is not an array or its length does not equal to 1
            if ((optionsArray instanceof Array) && (optionsArray.length == 2)) {
               
                var extraKey = optionsArray[1];
                var nativeObject = new IntelSecurityServicesWRC.SecureDataWRC();
                nativeObject.secureDataCreateFromSealedDataWRC(optionsArray[0], extraKey)
                  .then(function (jsonResponse) {
                      try {
                          var jsonResponseObject = JSON.parse(jsonResponse);
                          if (jsonResponseObject.code === 0) {
                              success(jsonResponseObject.data_handle);
                          }
                          else {
                              fail(jsonResponseObject.code);
                          }
                      }
                      catch (e) {
                          fail(ErrorCodes["Internal error occurred"]);
                      }
                  }, function (err) {
                      //error
                      fail(ErrorCodes["Internal error occurred"]);
                  }, function (prog) {
                      //shouldn't be called
                      fail(ErrorCodes["Internal error occurred"]);
                  });
            }
            else {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        catch (e) {
            fail(ErrorCodes["Internal error occurred"]);
        }

    },
    
    SecureDataChangeExtraKey: function (success, fail, optionsArray) {
        if ((optionsArray instanceof Array) && (optionsArray.length == 2)) {
            try {
                var instanceID = Number(optionsArray[0]);
                var extraKeyInstanceID = Number(optionsArray[1]);
                var nativeObject = new IntelSecurityServicesWRC.SecureDataWRC();
                nativeObject.secureDataChangeExtraKeyWRC(instanceID, extraKeyInstanceID)
                   .then(function (jsonResponse) {
                       try {
                           var jsonResponseObject = JSON.parse(jsonResponse);
                           if (jsonResponseObject.code === 0) {
                               success(jsonResponseObject.code);
                           }
                           else {
                               fail(jsonResponseObject.code);
                           }
                       }
                       catch (e) {
                           fail(ErrorCodes["Internal error occurred"]);
                       }
                   }, function (err) {
                       //error
                       fail(ErrorCodes["Internal error occurred"]);
                   }, function (prog) {
                       //shouldn't be called
                       fail(ErrorCodes["Internal error occurred"]);
                   });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }
    },

    SecureDataGetData: function (success, fail, optionsArray) {

        if ((optionsArray instanceof Array) && (optionsArray.length == 1)) {
            try {
                var instanceID = Number(optionsArray[0]);
                var nativeObject = new IntelSecurityServicesWRC.SecureDataWRC();
                nativeObject.secureDataGetDataWRC(instanceID)
                  .then(function (jsonResponse) {
                      try {
                          var jsonResponseObject = JSON.parse(jsonResponse);
                          if (jsonResponseObject.code === 0) {
                              success(jsonResponseObject.data);
                          }
                          else {
                              fail(jsonResponseObject.code);
                          }
                      }
                      catch (e) {
                          fail(ErrorCodes["Internal error occurred"]);
                      }
                  }, function (err) {
                      //error
                      fail(ErrorCodes["Internal error occurred"]);
                  }, function (prog) {
                      //shouldn't be called
                      fail(ErrorCodes["Internal error occurred"]);
                  });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }
    },

    SecureDataGetSealedData: function (success, fail, optionsArray) {

        if ((optionsArray instanceof Array) && (optionsArray.length == 1)) {
            try
            {
                var instanceID = Number(optionsArray[0]);
                        var nativeObject = new IntelSecurityServicesWRC.SecureDataWRC();
                nativeObject.secureDataGetSealedDataWRC(instanceID)
                          .then(function (jsonResponse) {
                              try {
                                  var jsonResponseObject = JSON.parse(jsonResponse);
                                  if (jsonResponseObject.code === 0) {
                                      success(jsonResponseObject.sealed_data);
                                  }
                                  else {
                                      fail(jsonResponseObject.code);
                                  }
                              }
                              catch (e) {
                                  fail(ErrorCodes["Internal error occurred"]);
                              }
                          }, function (err) {
                              //error
                              fail(ErrorCodes["Internal error occurred"]);
                          }, function (prog) {
                              //shouldn't be called
                              fail(ErrorCodes["Internal error occurred"]);
                          });

            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }
    },

    SecureDataGetTag: function (success, fail, optionsArray) {

        if ((optionsArray instanceof Array) && (optionsArray.length == 1)) {
            try {
                var instanceID = Number(optionsArray[0]);
                var nativeObject = new IntelSecurityServicesWRC.SecureDataWRC();
                nativeObject.secureDataGetTagWRC(instanceID)
                   .then(function (jsonResponse) {
                       try {
                           var jsonResponseObject = JSON.parse(jsonResponse);
                           if (jsonResponseObject.code === 0) {
                               success(jsonResponseObject.tag);
                           }
                           else {
                               fail(jsonResponseObject.code);
                           }
                       }
                       catch (e) {
                           fail(ErrorCodes["Internal error occurred"]);
                       }
                   }, function (err) {
                       //error
                       fail(ErrorCodes["Internal error occurred"]);
                   }, function (prog) {
                       //shouldn't be called
                       fail(ErrorCodes["Internal error occurred"]);
                   });

            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }

    },

    SecureDataGetPolicy: function (success, fail, optionsArray) {
        if ((optionsArray instanceof Array) && (optionsArray.length == 1)) {
            try {
                var instanceID = Number(optionsArray[0]);
                var nativeObject = new IntelSecurityServicesWRC.SecureDataWRC();
                nativeObject.secureDataGetPolicyWRC(instanceID)
                   .then(function (jsonResponse) {
                       try {
                           var jsonResponseObject = JSON.parse(jsonResponse);
                           if (jsonResponseObject.code === 0) {
                               var policy = jsonResponseObject.policy;
                               success(policy);
                           }
                           else {
                               fail(jsonResponseObject.code);
                           }
                       }
                       catch (e) {
                           fail(ErrorCodes["Internal error occurred"]);
                       }
                   }, function (err) {
                       //error
                       fail(ErrorCodes["Internal error occurred"]);
                   }, function (prog) {
                       //shouldn't be called
                       fail(ErrorCodes["Internal error occurred"]);
                   });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }


    },

    SecureDataGetOwners: function (success, fail, optionsArray) {

        if ((optionsArray instanceof Array) && (optionsArray.length == 1)) {
            try {
                var instanceID = Number(optionsArray[0]);
                        var nativeObject = new IntelSecurityServicesWRC.SecureDataWRC();
                        nativeObject.secureDataGetOwnersWRC(instanceID)
                           .then(function (jsonResponse) {
                               try {
                                   var jsonResponseObject = JSON.parse(jsonResponse);
                                   if (jsonResponseObject.code === 0) {
                                       success(jsonResponseObject.owners);
                                   }
                                   else {
                                       fail(jsonResponseObject.code);
                                   }
                               }
                               catch (e) {
                                   fail(ErrorCodes["Internal error occurred"]);
                               }
                           }, function (err) {
                               //error
                               fail(ErrorCodes["Internal error occurred"]);
                           }, function (prog) {
                               //shouldn't be called
                               fail(ErrorCodes["Internal error occurred"]);
                           });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }

    },

    SecureDataGetCreator: function (success, fail, optionsArray) {

        if ((optionsArray instanceof Array) && (optionsArray.length == 1)) {
            try {
                var instanceID = Number(optionsArray[0]);
                var nativeObject = new IntelSecurityServicesWRC.SecureDataWRC();
                nativeObject.secureDataGetCreatorWRC(instanceID)
                   .then(function (jsonResponse) {
                       try {
                           var jsonResponseObject = JSON.parse(jsonResponse);
                           if (jsonResponseObject.code === 0) {
                               success(jsonResponseObject.persona);
                           }
                           else {
                               fail(jsonResponseObject.code);
                           }
                       }
                       catch (e) {
                           fail(ErrorCodes["Internal error occurred"]);
                       }
                   }, function (err) {
                       //error
                       fail(ErrorCodes["Internal error occurred"]);
                   }, function (prog) {
                       //shouldn't be called
                       fail(ErrorCodes["Internal error occurred"]);
                   });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }


    },

    SecureDataGetWebOwners: function (success, fail, optionsArray) {

        if ((optionsArray instanceof Array) && (optionsArray.length == 1)) {
            try {
                var instanceID = Number(optionsArray[0]);
                var nativeObject = new IntelSecurityServicesWRC.SecureDataWRC();
                nativeObject.secureDataGetTrustedWebDomainsListWRC(instanceID)
                   .then(function (jsonResponse) {
                       try {
                           var jsonResponseObject = JSON.parse(jsonResponse);
                           if (jsonResponseObject.code === 0) {
                                   success(jsonResponseObject.trusted_domain_list);
                           }
                           else {
                               fail(jsonResponseObject.code);
                           }
                       }
                       catch (e) {
                           fail(ErrorCodes["Internal error occurred"]);
                       }
                   }, function (err) {
                       //error
                       fail(ErrorCodes["Internal error occurred"]);
                   }, function (prog) {
                       //shouldn't be called
                       fail(ErrorCodes["Internal error occurred"]);
                   });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
    },

    SecureDataDestroy: function (success, fail, optionsArray) {
        if ((optionsArray instanceof Array) && (optionsArray.length == 1)) {
            try {
                var instanceID = Number(optionsArray[0]);
                var nativeObject = new IntelSecurityServicesWRC.SecureDataWRC();
                nativeObject.secureDataDestroyWRC(instanceID)
                   .then(function (jsonResponse) {
                       try {
                           var jsonResponseObject = JSON.parse(jsonResponse);
                           if (jsonResponseObject.code === 0) {
                               success();
                           }
                           else {
                               fail(jsonResponseObject.code);
                           }
                       }
                       catch (e) {
                           fail(ErrorCodes["Internal error occurred"]);
                       }
                   }, function (err) {
                       //error
                       fail(ErrorCodes["Internal error occurred"]);
                   }, function (prog) {
                       //shouldn't be called
                       fail(ErrorCodes["Internal error occurred"]);
                   });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }


    }

};

//SecureStorage class		
var secureStorage = {
    SecureStorageRead: function (success, fail, optionsArray) {

        if ((optionsArray instanceof Array) && (optionsArray.length == 3)) {
            try {

                var id = optionsArray[0];
                var storageType = optionsArray[1];
                var extraKey = optionsArray[2];
                var nativeObject = new IntelSecurityServicesWRC.SecureStorageWRC();
                nativeObject.secureStorageReadWRC(id, storageType, extraKey)
                   .then(function (jsonResponse) {
                       try {
                           var jsonResponseObject = JSON.parse(jsonResponse);
                           if (jsonResponseObject.code === 0) {
                               success(jsonResponseObject.data_handle);
                           }
                           else {
                               fail(jsonResponseObject.code);
                           }
                       }
                       catch (e) {
                           fail(ErrorCodes["Internal error occurred"]);
                       }
                   }, function (err) {
                       //error
                       fail(ErrorCodes["Internal error occurred"]);
                   }, function (prog) {
                       //shouldn't be called
                       fail(ErrorCodes["Internal error occurred"]);
                   });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }


    },

    SecureStorageWrite: function (success, fail, optionsArray) {
        if ((optionsArray instanceof Array) && (optionsArray.length == 3)) {
            try
            {
                var id=optionsArray[0];
		var storage_type=optionsArray[1];
		var handle_id=optionsArray[2];
		var nativeObject = new IntelSecurityServicesWRC.SecureStorageWRC();
                nativeObject.secureStorageWriteWRC(id, storage_type, handle_id)
                    .then(function (jsonResponse) {
                        try {
                            var jsonResponseObject = JSON.parse(jsonResponse);
                            if (jsonResponseObject.code === 0) {
                                success();
                            }
                            else {
                                fail(jsonResponseObject.code);
                            }
                        }
                        catch (e) {
                            fail(ErrorCodes["Internal error occurred"]);
                        }
                    }, function (err) {
                        //error
                        fail(ErrorCodes["Internal error occurred"]);
                    }, function (prog) {
                        //shouldn't be called
                        fail(ErrorCodes["Internal error occurred"]);
                    });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }
    },

    SecureStorageDelete: function (success, fail, optionsArray) {
        if ((optionsArray instanceof Array) && (optionsArray.length == 2)) {
            try {
                var id=optionsArray[0];
		var storage_type=optionsArray[1];
		var nativeObject = new IntelSecurityServicesWRC.SecureStorageWRC();
                nativeObject.secureStorageDeleteWRC(id,storage_type)
                   .then(function (jsonResponse) {
                       try {
                           var jsonResponseObject = JSON.parse(jsonResponse);
                           if (jsonResponseObject.code === 0) {
                               success();
                           }
                           else {
                               fail(jsonResponseObject.code);
                           }
                       }
                       catch (e) {
                           fail(ErrorCodes["Internal error occurred"]);
                       }
                   }, function (err) {
                       //error
                       fail(ErrorCodes["Internal error occurred"]);
                   }, function (prog) {
                       //shouldn't be called
                       fail(ErrorCodes["Internal error occurred"]);
                   });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }


    }
};

//SecureTransport class		
var secureTransport = {

    SecureTransportOpen: function (success, fail, optionsArray) {

        if ((optionsArray instanceof Array) && (optionsArray.length == 4)) {
            try {
                var url = optionsArray[0];
                var method = optionsArray[1];
                var serverKey = optionsArray[2];
                var timeout = optionsArray[3];

                var nativeObject = new IntelSecurityServicesWRC.SecureTransportWRC();
                nativeObject.secureTransportOpenWRC(url, method, serverKey, timeout)
                    .then(function (jsonResponse) {
                        try {
                            var jsonResponseObject = JSON.parse(jsonResponse);
                            if (jsonResponseObject.code === 0) {
                                success(jsonResponseObject.InstanceID);
                            }
                            else {
                                fail(jsonResponseObject.code);
                            }
                        }
                        catch (e) {
                            fail(ErrorCodes["Internal error occurred"]);
                        }
                    }, function (err) {
                        //error
                        fail(ErrorCodes["Internal error occurred"]);
                    }, function (prog) {
                        //shouldn't be called
                        fail(ErrorCodes["Internal error occurred"]);
                    });
            }

            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }

        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }
    },

   SecureTransportSetURL: function (success, fail, optionsArray) {
        if ((optionsArray instanceof Array) && (optionsArray.length == 3)) {
            try {
                var instanceID = optionsArray[0];
                var url = optionsArray[1];
                var serverKey = optionsArray[2];
                var nativeObject = new IntelSecurityServicesWRC.SecureTransportWRC();
                nativeObject.secureTransportSetURLWRC(instanceID, url, serverKey)
                    .then(function (jsonResponse) {
                        try {
                            var jsonResponseObject = JSON.parse(jsonResponse);
                            if (jsonResponseObject.code === 0) {
                                success();
                            }
                            else {
                                fail(jsonResponseObject.code);
                            }
                        }
                        catch (e) {
                            fail(ErrorCodes["Internal error occurred"]);
                        }
                    }, function (err) {
                        //error
                        fail(ErrorCodes["Internal error occurred"]);
                    }, function (prog) {
                        //shouldn't be called
                        fail(ErrorCodes["Internal error occurred"]);
                    });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }
    },

    SecureTransportSetHeaders: function (success, fail, optionsArray) {
        if ((optionsArray instanceof Array) && (optionsArray.length == 2)) {
            try {
                var instanceID = optionsArray[0];
                var headers = optionsArray[1];
                var nativeObject = new IntelSecurityServicesWRC.SecureTransportWRC();
                nativeObject.secureTransportSetHeadersWRC(instanceID, headers)
                    .then(function (jsonResponse) {
                        try {
                            var jsonResponseObject = JSON.parse(jsonResponse);
                            if (jsonResponseObject.code === 0) {
                                success();
                            }
                            else {
                                fail(jsonResponseObject.code);
                            }
                        }
                        catch (e) {
                            fail(ErrorCodes["Internal error occurred"]);
                        }
                    }, function (err) {
                        //error
                        fail(ErrorCodes["Internal error occurred"]);
                    }, function (prog) {
                        //shouldn't be called
                        fail(ErrorCodes["Internal error occurred"]);
                    });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }
    },

    SecureTransportSendRequest: function (success, fail, optionsArray) {
        if ((optionsArray instanceof Array) && (optionsArray.length == 4)) {
            try {
                var instanceID = optionsArray[0];
                var requestBody = optionsArray[1];
                var requestFormat = optionsArray[2];
                var secureDataDescriptors = optionsArray[3];
                var nativeObject = new IntelSecurityServicesWRC.SecureTransportWRC();
                nativeObject.secureTransportSendRequestWRC(instanceID, requestBody, requestFormat, secureDataDescriptors)
                    .then(function (jsonResponse) {
                        try{
                            var jsonResponseObject = JSON.parse(jsonResponse);
                            if (jsonResponseObject.code === 0) {
                                
                                success({ 'responseHeader': jsonResponseObject.headers, 'responseBody': jsonResponseObject.body,'responseHttpStatus': jsonResponseObject.http_status });
                            }
                            else {
                                fail(jsonResponseObject.code);
                            }
                        }
                        catch (e) {
                            fail(ErrorCodes["Internal error occurred"]);
                        }
                    }, function (err) {
                        //error
                        fail(ErrorCodes["Internal error occurred"]);
                    }, function (prog) {
                        //shouldn't be called
                        fail(ErrorCodes["Internal error occurred"]);
                    });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }


    },
    
    SecureTransportAbort: function (success, fail, optionsArray) {
        if ((optionsArray instanceof Array) && (optionsArray.length == 1)) {
            try {
                var instanceID = optionsArray[0];
                var nativeObject = new IntelSecurityServicesWRC.SecureTransportWRC();
                nativeObject.secureTransportAbortWRC(instanceID)
                    .then(function (jsonResponse) {
                        try {
                            var jsonResponseObject = JSON.parse(jsonResponse);
                            if (jsonResponseObject.code === 0) {
                                success();
                            }
                            else {
                                fail(jsonResponseObject.code);
                            }
                        }
                        catch (e) {
                            fail(ErrorCodes["Internal error occurred"]);
                        }
                    }, function (err) {
                        //error
                        fail(ErrorCodes["Internal error occurred"]);
                    }, function (prog) {
                        //shouldn't be called
                        fail(ErrorCodes["Internal error occurred"]);
                    });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }
    },

    SecureTransportDestroy: function (success, fail, optionsArray) {
        if ((optionsArray instanceof Array) && (optionsArray.length == 1)) {
            try {
                var instanceID = optionsArray[0];
                var nativeObject = new IntelSecurityServicesWRC.SecureTransportWRC();
                nativeObject.secureTransportDestroyWRC(instanceID)
                    .then(function (jsonResponse) {
                        try {
                            var jsonResponseObject = JSON.parse(jsonResponse);
                            if (jsonResponseObject.code === 0) {
                                success();
                            }
                            else {
                                fail(jsonResponseObject.code);
                            }
                        }
                        catch (e) {
                            fail(ErrorCodes["Internal error occurred"]);
                        }
                    }, function (err) {
                        //error
                        fail(ErrorCodes["Internal error occurred"]);
                    }, function (prog) {
                        //shouldn't be called
                        fail(ErrorCodes["Internal error occurred"]);
                    });
                
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }


    },

    SecureTransportSetMethod: function (success, fail, optionsArray) {
        if ((optionsArray instanceof Array) && (optionsArray.length == 2)) {
            try {
                var instanceID = optionsArray[0];
                var method = optionsArray[1];
                var nativeObject = new IntelSecurityServicesWRC.SecureTransportWRC();
                nativeObject.secureTransportSetMethodWRC(instanceID, method)
                    .then(function (jsonResponse) {
                        try {
                            var jsonResponseObject = JSON.parse(jsonResponse);
                            if (jsonResponseObject.code === 0) {
                                success();
                            }
                            else {
                                fail(jsonResponseObject.code);
                            }
                        }
                        catch (e) {
                            fail(ErrorCodes["Internal error occurred"]);
                        }
                    }, function (err) {
                        //error
                        fail(ErrorCodes["Internal error occurred"]);
                    }, function (prog) {
                        //shouldn't be called
                        fail(ErrorCodes["Internal error occurred"]);
                    });
            }
            catch (e) {
                fail(ErrorCodes["Internal error occurred"]);
            }
        }
        else {
            fail(ErrorCodes["Internal error occurred"]);
        }


    },

};

//imlemenation of all the APIs by thier API name on the bridge (cordova.exec)
module.exports = {
    GlobalInit: function (success, fail, option) {

		global.GlobalInit(success, fail, option);

    },
    //Secure Data
    SecureDataCreateFromData: function (success, fail, option) {

        secureData.SecureDataCreateFromData(success, fail, option);

    },
    SecureDataCreateFromSealedData: function (success, fail, sealedData) {

        secureData.SecureDataCreateFromSealedData(success, fail, sealedData);
    },
    SecureDataChangeExtraKey: function (success, fail, sealedData) {

        secureData.SecureDataChangeExtraKey(success, fail, sealedData);
    },
    SecureDataGetData: function (success, fail, instanceID) {

        secureData.SecureDataGetData(success, fail, instanceID);
    },
    SecureDataGetSealedData: function (success, fail, instanceID) {

        secureData.SecureDataGetSealedData(success, fail, instanceID);
    },
    SecureDataGetTag: function (success, fail, instanceID) {

        secureData.SecureDataGetTag(success, fail, instanceID);
    },
    SecureDataGetPolicy: function (success, fail, instanceID) {

        secureData.SecureDataGetPolicy(success, fail, instanceID);
    },
    SecureDataGetOwners: function (success, fail, instanceID) {

        secureData.SecureDataGetOwners(success, fail, instanceID);
    },
    SecureDataGetCreator: function (success, fail, instanceID) {

        secureData.SecureDataGetCreator(success, fail, instanceID);
    },
    SecureDataGetWebOwners: function (success, fail, instanceID) {

        secureData.SecureDataGetWebOwners(success, fail, instanceID);
    },
    //Secure Storage
    SecureDataDestroy: function (success, fail, instanceID) {

        secureData.SecureDataDestroy(success, fail, instanceID);
    },
    SecureStorageRead: function (success, fail, option) {

        secureStorage.SecureStorageRead(success, fail, option);
    },
    SecureStorageWrite: function (success, fail, option) {

        secureStorage.SecureStorageWrite(success, fail, option);
    },
    SecureStorageDelete: function (success, fail, option) {

        secureStorage.SecureStorageDelete(success, fail, option);
    },
    //Secure Transport
    SecureTransportOpen: function (success, fail, instanceID) {
        secureTransport.SecureTransportOpen(success, fail, instanceID);
    },
    SecureTransportSetURL: function (success, fail, option) {

        secureTransport.SecureTransportSetURL(success, fail, option);
    },
    SecureTransportSetMethod: function (success, fail, option) {

        secureTransport.SecureTransportSetMethod(success, fail, option);
    },
    SecureTransportSetHeaders: function (success, fail, option) {

        secureTransport.SecureTransportSetHeaders(success, fail, option);
    },
    SecureTransportSendRequest: function (success, fail, option) {

        secureTransport.SecureTransportSendRequest(success, fail, option);
    },
    SecureTransportAbort: function (success, fail, option) {
        secureTransport.SecureTransportAbort(success, fail, option);
    },
    SecureTransportDestroy: function (success, fail, option) {

        secureTransport.SecureTransportDestroy(success, fail, option);
    }
};

//Namespace of the bridge
require("cordova/exec/proxy").add("IntelSecurity", module.exports);


