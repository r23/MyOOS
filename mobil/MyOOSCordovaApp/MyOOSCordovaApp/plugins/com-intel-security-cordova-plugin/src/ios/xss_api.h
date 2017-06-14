#ifndef __SECURITY_SERVICES_API_H__
#define __SECURITY_SERVICES_API_H__

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

/** @file 
	@brief Main API header.
*/

#include "xss_types.h"
#include "xss_log.h"

#ifdef __cplusplus
extern "C" {
#endif


/** @defgroup Globals Globals
 @{
#define SSERVICE_VERSION 0x00010000
*/
sservice_result_t sservice_global_init_start(void);
sservice_result_t sservice_global_init_end(void);
sservice_result_t sservice_global_release(void);
sservice_result_t sservice_config_set(IN config_id_enum_t ConfigID, IN char const *pData, IN sservice_size_t DataSize ) ;
/** @} */ 


/** @defgroup SecureData SecureData
 @{
*/
/** Function removes object handle from HandleManager table and destroys referenced SecureData object.
* @param [in] data_handle references object to be destroyed
* @see sservice_data_handle_t
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_destroy
	( 
	IN_OUT sservice_data_handle_t data_handle
	) ;

/** Function  Creates and fills secure data object and creates handle for reference it
* @param [in] data_size - size of data to add to secure data object
* @param [in] data - data to add to secure data object
* @param [in] tag_size- size of tag to attach to secure data object
* @param [in] tag - tag to attach to secure data object
* @param [in] access_policy - policy to attach to secure data object
* @param [in] creator - creator persona 
* @param [in] number_of_owners - number of data owners
* @param [in] owners - list f data owners
* @param [in] authentication_token - token authenticating the application
* @param [out] data_handle - return value: handle that references created data object
* @see  
* @see  sservice_secure_data_policy_t
* @see  sservice_persona_id_t 
* @see  sservice_size_t 
* @see  sservice_persona_id_t,
* @see  sservice_authentication_token_t 
* @see  sservice_data_handle_t
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_create_from_data
	(
	IN  sservice_size_t					data_size,
	IN  const char*						data,
	IN  sservice_size_t					tag_size,
	IN  const char*						tag,
	IN  sservice_secure_data_policy_t*  access_policy,
	IN  sservice_data_handle_t			extra_key,
	IN  sservice_persona_id_t			creator,
	IN  sservice_size_t					number_of_owners,
	IN  sservice_persona_id_t*			owners,
	IN  sservice_authentication_token_t authentication_token,
	IN  const char*						trusted_web_domains,
	OUT sservice_data_handle_t*			data_handle
	);

/** Function Creates and fills secure data object from client-supplied Sealed Data
* @param [in] sealed_data_size size of sealed data
* @param [in] sealed_data - sealed data pointer with encrypted data
* @param [out] data_handle - return value: handle that references created data object
* @see sealed_data 
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_create_from_sealed_data
	(
	IN  sservice_size_t			sealed_data_size,
	IN  const char*				sealed_data,
	IN  sservice_data_handle_t  extra_key,
	OUT sservice_data_handle_t* data_handle
	);

/** Function changes the in-use extraKey pass for the given pass in the secure data instance.
* @param [in] data_handle - data handle identifing the securedata onject we want the extrakey changes in.
* @param [in] extra_key - the extrakey secure data instance containing the extra key pass.
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_change_extraKey
	(
	IN  sservice_data_handle_t data_handle,
	IN  sservice_data_handle_t  extra_key
	);

/** Function  retrieves size of buffer required to retrieve the plain data from Secured data object
* @param [in] data_handle identifying the secure data object
* @param [out] data_size pointer to buffer, receiving the return value.
* @see  
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_get_size
	(
	IN  sservice_data_handle_t data_handle,
	OUT  sservice_size_t* data_size
	);

/** Function retrieves security policy information for referenced( by handle ) secure data object. 
* @param [in] data_handle identifying the secure data object
* @param [out] access_policy pointer to buffer, receiving the return value.
* @see  
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_get_policy
	(
	IN  sservice_data_handle_t data_handle,
	OUT sservice_secure_data_policy_t* access_policy
	);

/** Function retrieves raw( plain ) data information for referenced( by handle ) secure data object.
* @param [in] data_handle identifying the secure data object
* @param [in] authentication_token - information for authentication the application
* @param [in] buffer_size - size of buffer which will receive data.
* @param [out] buffer pointer to buffer, receiving the data.
* @see  
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_get_data
	(
	IN  sservice_data_handle_t data_handle,
	IN  sservice_authentication_token_t authentication_token,
	IN  sservice_size_t buffer_size,
	OUT char* buffer
	);

/** Function retrieves buffer necessary to retrieve all information (encrypted) for referenced( by handle ) secure data object.
* @param [in] data_handle identifying the secure data object
* @param [out] sealed_data_size pointer to buffer, receiving the return value.
* @see  
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_get_sealed_size
	(
	IN  sservice_data_handle_t data_handle,
	OUT  sservice_size_t* sealed_data_size
	);

/** Function retrieves all information( including encrypted data ) for referenced( by handle ) secure data object.
* @param [in] data_handle identifying the secure data object
* @param [in] buffer_size - size of buffer which will receive raw sealed data.
* @param [out] buffer pointer to buffer, receiving the sealed data.
* @see sealed_data 
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_get_sealed_data
	(
	IN  sservice_data_handle_t data_handle,
	IN  sservice_size_t buffer_size,
	OUT char* buffer
	);

/** Function retrieves number of owners for referenced( by handle ) secure data object.
* @param [in] data_handle identifying the secure data object
* @param [out] number_of_owners - pointer to buffer, receiving the return value.
* @see  
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_get_number_of_owners
	(
	IN  sservice_data_handle_t  data_handle,
	OUT  sservice_size_t* number_of_owners
	);

/** Function retrieves list of owners for referenced( by handle ) secure data object.
* @param [in] data_handle identifying the secure data object
* @param [in] buffer_size - size of buffer which will receive data.
* @param [out] owners_buffer pointer to buffer, receiving list of owners.
* @see  
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_get_owners
	(
	IN  sservice_data_handle_t  data_handle,
	IN  sservice_size_t buffer_size,
	OUT sservice_persona_id_t* owners_buffer
	);

/** Function retrieves size of buffer required to retrieve tag for referenced( by handle ) secure data object.
* @param [in] data_handle identifying the secure data object
* @param [out] tag_size pointer to buffer, receiving the return value.
* @see  
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t  sservice_securedata_get_tag_size
	(
	IN  sservice_data_handle_t  data_handle,
	OUT  sservice_size_t * tag_size
	);

/** Function retrieves tag for referenced( by handle ) secure data object.
* @param [in] data_handle identifying the secure data object
* @param [in] buffer_size - size of buffer which will receive tag.
* @param [out] buffer pointer to buffer, receiving the tag.
* @see  
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_get_tag
	(
	IN  sservice_data_handle_t  data_handle,
	IN  sservice_size_t  buffer_size,
	OUT char* buffer
	);

/** Function retrieves creator persona id for referenced( by handle ) secure data object.
* @param [in] data_handle identifying the secure data object
* @param [out] creator - pointer to buffer, receiving the return value.
* @see  
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securedata_get_creator
	(
	IN  sservice_data_handle_t   data_handle,
	OUT sservice_persona_id_t* creator
	);


sservice_result_t sservice_securedata_get_trusted_web_domains_list_size
	(
	IN  sservice_data_handle_t data_handle,
	OUT  sservice_size_t* list_size
	);
	
sservice_result_t sservice_securedata_get_trusted_web_domains_list
	(
	IN  sservice_data_handle_t data_handle,
	IN  sservice_size_t buffer_size,
	OUT char* buffer
	);
/** @} */ 
/** @defgroup SecureStorage SecureStorage
 @{
*/

/** Function  reads secure data from persistent media. Data on medium is in #sealed_data format
* @param [in] id- parameter, identifying the storage medium( name of file )
* @param [in] type - parameter, identifying the storage medium ( type, file/cloud/etc )
* @param [out] data_handle buffer to put the handler to
* @see sealed_data 
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securestorage_read
	(
	IN  const sservice_string_t id,
	IN  sservice_secure_storage_type_t type,
	IN sservice_data_handle_t extra_key,
	OUT sservice_data_handle_t* data_handle
	);

/** Function  stores secure data to persistent media. Data is written in #sealed_data format
* @param [in] id- parameter, identifying the storage medium( name of file )
* @param [in] type - parameter, identifying the storage medium ( type, file/cloud/etc )
* @param [in] data_handle identifying the secure data object
* @see sealed_data 
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securestorage_write
	(
	IN  const sservice_string_t id,
	IN  sservice_secure_storage_type_t type,
	IN  sservice_data_handle_t data_handle
	);


/** Function deletes secure storage from persistent media.
* @param [in] id- parameter, identifying the storage medium( name of file )
* @param [in] type - parameter, identifying the storage medium ( type, file/cloud/etc )
* @see  
* @return sservice_result_t, indicating success/failure error code.
*/
sservice_result_t sservice_securestorage_delete
	(
	IN  const sservice_string_t id,
	IN  sservice_secure_storage_type_t type
	);

sservice_result_t sservice_securetransport_open
	(
	IN  const char* url,
	IN  const char* pinned_server_public_certificate,
	IN  const char* client_private_certificate,
	IN  sservice_secure_transport_timeout_t timeout,
	IN  sservice_http_method_t method,
	OUT sservice_transport_handle_t* handle
	);

sservice_result_t sservice_securetransport_set_method
	(
	IN sservice_transport_handle_t handle,
    IN  sservice_http_method_t method
	);    

sservice_result_t sservice_securetransport_set_headers
	(
	IN  sservice_transport_handle_t handle,
	IN  const char* headers
	);

sservice_result_t sservice_securetransport_set_url
	(
	IN sservice_transport_handle_t handle,
    IN  const char* url,
	IN  const char* public_key_pinning
	);


sservice_result_t sservice_securetransport_send_request
	(
	IN  sservice_transport_handle_t handle,	
	IN  const char* request, // utf-8
	IN  sservice_secure_transport_content_type_t request_format,
	IN  const char* desc_str, // utf-8
	OUT unsigned long* http_status_code,
    OUT  sservice_size_t* response_header_size,
    OUT  sservice_size_t* response_body_size
	);
 
sservice_result_t sservice_securetransport_get_response_header
    (
	IN  sservice_transport_handle_t handle,
	IN  sservice_size_t buffer_size,
    OUT char* buffer
	);

    
sservice_result_t sservice_securetransport_get_response_body
    (
	IN  sservice_transport_handle_t handle,
    IN  sservice_size_t buffer_size,
    OUT char* buffer
    );

sservice_result_t sservice_securetransport_abort
	(
	IN  sservice_transport_handle_t handle
	);

sservice_result_t sservice_securetransport_destroy
	(
	IN  sservice_transport_handle_t handle
	);

void sservice_log(  sservice_log_source_t log_source, sservice_log_level_t log_level, char const *format_str, ... ) ;

   
#ifdef __cplusplus
}
#endif

#endif
