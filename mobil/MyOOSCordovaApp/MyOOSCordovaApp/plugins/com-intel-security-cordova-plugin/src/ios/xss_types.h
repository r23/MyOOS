#ifndef __SECURITY_SERVICES_TYPES_H__
#define __SECURITY_SERVICES_TYPES_H__

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
	@brief Main API type declarations.
*/

#include <stdint.h>

#define IN
#define OUT
#define IN_OUT

#if defined _MSC_VER
#   define ALIGN(n) __declspec(align(n))
#else
#   define ALIGN(n) __attribute__ ((aligned(n))) __attribute__((packed))
#endif 
#define ALIGN1 ALIGN(1)

#ifndef UINT64_MAX
typedef unsigned long long uint64_t ;
#endif
typedef uint64_t sservice_handle_t ;   /**< used to reference the object*/

#ifndef _UINT32_T
typedef unsigned int uint32_t;
#define _UINT32_T
#endif

#ifndef UINT16_MAX
#ifdef _UI16_MAX
	#define UINT16_MAX  _UI16_MAX
#else
	#define UINT16_MAX  (0xffff)
#endif
#endif
#ifndef UINT32_MAX
#ifdef _UI32_MAX
	#define UINT32_MAX  _UI32_MAX
#else
	#define UINT32_MAX  (0xffffffff)
#endif
#endif
#ifndef UINT64_MAX
#ifdef _UI64_MAX
#define UINT64_MAX  _UI64_MAX
#else
#define UINT64_MAX  (0xffffffffffffffffull)
#endif
#endif

typedef char utf8_t ;
typedef uint32_t sservice_size_t;		/**< defines size of data*/
typedef unsigned short uint16_t;

#ifdef _MSC_VER
#pragma pack( push, 1)
#endif
/**
 * The enumeration of SService Result bits
 */
typedef enum 
{
	SSERVICE_ERROR = 3,  /**< Error */
	SSERVICE_WARN  = 1,  /**< Success with warning */
	SSERVICE_SUCCESS = 0,/**< Success! */
} sservice_error_bits_t;


/**
 * structure used to return status from functions.
 * @see CREATE_SUCCESS
 * @see CREATE_ERROR
 * @see IS_SUCCESS
 * @see IS_FAILED
 */
typedef  union
{
	uint32_t raw;
    struct ALIGN1{
        uint32_t is_success			:2;		/**< sservice_error_bits_t signs success or failure, @see sservice_error_bits_t*/
        uint32_t reserved			: 10;   /**< currently not used space */
        uint32_t error_or_warn_code	: 20 ;  /**< error code, @see ss_error_code_t*/
    };
} sservice_result_t;

#define SSERVICE_NULL_PERSONA_ID		(0)
#define SSERVICE_INVALID_PERSONA_ID		(UINT64_MAX)
#define SSERVICE_INVALID_HANDLE			(0)
#define SSERVICE_SEALED_DATA_VERSION	(1)

typedef uint64_t sservice_authentication_token_t ;

typedef sservice_handle_t sservice_data_handle_t ;			/**< used to reference the SecureData object*/
typedef sservice_handle_t sservice_transport_handle_t ;     /**< used to reference the SecureTransport object*/

/**
 * Type defined for string transfer from client JS application to OS specific code.
 */
typedef void const * sservice_string_t ;

typedef uint64_t sservice_persona_id_t;						/**< used to identify the persona entity*/
typedef uint64_t sservice_sensitivity_level_t;				/**< used to identify the sensitivity level of data*/


/**
 * Part of policy, defines which application can have access to data item
 */
typedef enum
{
	SPECIFIC_APPLICATION = 0,								/**< Specific application*/
	ANY_APPLICATION,										/**< any SService API based application*/
} sservice_application_access_control_type_t ;

/**
 * Part of policy, defines which device(s) can have access to data item
 */
typedef enum
{
	SPECIFIC_DEVICE = 0,									/**< Specific device*/
	ANY_DEVICE,												/**< Any device*/
} sservice_locality_type_t;


/**
 * this is the flags structure for the policy object.
 */
typedef union
{
	uint32_t raw;
    struct ALIGN1 {
        uint16_t no_store	: 1;	/**< noStore flag */
        uint16_t no_read	: 1;    /**< noRead flag */
        uint32_t reserved	: 30;  
    };
} sservice_policy_flags_t;

/**
 * The policy declaration: defines which application, in which conditions can access the data
 */
typedef struct ALIGN1 
{
	sservice_application_access_control_type_t	application_policy;		/**< which application*/
	sservice_locality_type_t				    device_policy;			/**< on which device*/
	sservice_sensitivity_level_t			    sensitivity_level;		/**< sensitivity level of data*/
	sservice_policy_flags_t						flags;
} sservice_secure_data_policy_t;

/**
 * Defines destination for SecureStorage
 */
typedef enum 
{
	STORAGE_TYPE_LOCAL = 0,									/**< Only local storage is currently supported*/
}sservice_secure_storage_type_t ;


/**
 * definitions
 */
#define APPLICATION_TOKEN_SIZE (32)				/**< application token (SHA?) */
#define ENCRYPTION_KEY_SIZE (256)				/**< RSA2048 key */
#define SIGNATURE_SIZE ENCRYPTION_KEY_SIZE		/**< RSA2048 key */
#define MAX_SENSITIVITY_LEVELS (1)				/**< currently 1, will be increased */
#define ILLEGAL_SENSITIVITY_LEVEL (UINT32_MAX)
#define MAX_DATA_SIZE (0x19000000)				/**< Maximal Data size - 400 MB. */
#define MAX_TAG_SIZE (0x19000000)				/**< Maximal Tag size - 400 MB. */
#define MAX_SEALED_DATA_SIZE (0x1F400000)				/**< Maximal Tag size - 500 MB. */
#define MAX_WEB_DOMAINS_LIST_LENGTH (4096)		/**< Maximal Web owners. size - 4kB. */
#define MAX_OWNERS_COUNT (1)
#define MAX_BUFFER_SIZE_SECURE_TRANSPORT_ID (64)
#define MAX_SECURE_TRANSPORT_URL_SIZE (16384)			//16K
#define MAX_SECURE_TRANSPORT_TIMEOUT (120000)		    //120 seconds
#define MAX_SECURE_TRANSPORT_CERTIFICATE_SIZE (65536)	//64KB
#define MAX_SECURE_TRANSPORT_DATA_SIZE (83886080)		//80MB
#define MAX_SECURE_TRANSPORT_RESPONSE_DATA_SIZE (83886080)//80MB
#define MAX_SECURE_TRANSPORT_DESCRIPTOR_SIZE (8192)		//8KB
#define MAX_SECURE_TRANSPORT_SUM_HEADERS_SIZE (32768)	//32KB
#define AES128_KEY_SIZE             (16)
#define AES128_CBC_IV_SIZE          AES128_KEY_SIZE
#define AES128_GCM_IV_SIZE          (12)
#define AES128_SALT_SIZE            (6)

#define CURRENT_STORAGE_FILE_HEADER_VERSION (1)
#define MAX_STORAGE_ID_LENGTH_DEFAULT (1024)
#ifndef MAX_STORAGE_ID_LENGTH_EXTRA
#define MAX_STORAGE_ID_LENGTH_EXTRA (0)
#endif
#define MAX_STORAGE_ID_LENGTH  ((MAX_STORAGE_ID_LENGTH_DEFAULT) + (MAX_STORAGE_ID_LENGTH_EXTRA))       ///size of ID defined as 1k characters( on any language ) that can take maximum 4k bytes in utf8
#define STORAGE_FILE_HEADER_ID (0x5f454741524f5453ul) //STORAGE_

/**
 * @struct storage_file_header_t
 * File Header for storage file structure definition 
 *
 *	|	Field					|	Size [bytes]					|	Comments		                        |
 *  | :------------------------ | :-------------------------------: | :---------------------------------------- |
 *	| HeaderID                  |	8	                            | STORAGE_; used to distinguish file type   |
 *	| headerVersion             |	8	                            | Revision ID for file header		        |
 *	| headerSize                |	4	                            | Size of whole header              		|
 *	| dataOffset                |	4	                            | Offset of file data( after header + ID )	|
 *	| ID                        |	dataOffset - headerSize         | User required data ID                 	|
 */

//Add file structure
typedef struct ALIGN1 
{
	uint64_t headerID;
	uint64_t headerVersion;
	sservice_size_t headerSize;
	sservice_size_t sdOffset; // sdOffset
} storage_file_header_t;


/**
 * @struct sealed_data
 * Sealed data structure definition 
 * variable size structure, including the following:
 * @see sealed_data_header_t 
 * @see owner_data_t
 *
 *	|	Part  			|	Field					|	Size [bytes]					|	Comments		|
 *  | :---------------- | :------------------------ | :-------------------------------: | :----------------	|
 *	| Header			|							|	sizeof(sealed_data_header_t)	|					|
 *	|					|	Version					|		4							|	Must be 1		|
 *	|					|	Total size				|		4							|					|
 *	|					|	Data size (plaintext)	|		4							|					|
 *	|					|	Encrypted Data size		|		4							|					|
 *	|					|	Tag size				|		4							|					|
 *	|					|	Application token		|		32							|	SHA256 result	|
 *	|					|	Locality token			|		8							|	Must be 0		|
 *	|					|	RESERVED0				|		8							|	Must be 0		|
 *	|					|	Sensitivity level		|		4							|	Must be 0		|
 *	|					|	Creator ID				|		8							|	Must be 0		|
 *  |					|	Web Domains List size   |       4							|					|
 *	|					|	Number of owners		|		4							|	Must be 1		|
 *	|	Owners			|							|sizeof(owner_data_t)*NumberOfOwners|                   |
 *	|					|	Owner ID				|		8							|	Must be 0		|
 *	|					|	Encrypted Key			|		256							|					|
 *	| Web Domains List	|	Web Domains List		|	Web Domains List size			| PUNYCODE encoded  |
 *	|					|							|									| ASCII string 		|
 *	|	Data			|							|									|					|
 *	|					|	Encrypted Data			|		Variable					|					|
 *	|					|	Tag						|		Variable					|					|
 *	|	Signature		|							|									|					|
 *	|					|	Signature				|		256							|					|
 *	|					|							|									|					|
 */

/**
 * Owners
 */
typedef struct ALIGN1  
{
	sservice_persona_id_t owner_id;							/**< Persona ID of owner */
	char encryption_key[ENCRYPTION_KEY_SIZE] ;				/**< AES encryption key, which used for encryption of data, encrypted by RSA key of current owner*/
}owner_data_t;


/**\struct sealed_data_header_t 
 * \brief it is a header of Sealed Data structure
 */
typedef struct ALIGN1 
{
	uint32_t version ;											/**< version of sealed data header */
	sservice_size_t total_size ;								/**< size of whole sealed data */
	sservice_size_t data_size ;									/**< size of plain data, encrypted inside of sealed data */
	sservice_size_t encrypted_data_size ;						/**< size of data, encrypted inside of sealed data */
	sservice_size_t tag_size ;									/**< size of TAG */
	char application_token[APPLICATION_TOKEN_SIZE] ;			/**< Application token, authenticating the application */
	sservice_locality_type_t locality_token;					/**< details of device */
	sservice_sensitivity_level_t sensitivity_level ;			/**<  */
	sservice_persona_id_t creator_id ;							/**< Creator persona */
	sservice_policy_flags_t flags;								/*policy flags*/
	sservice_size_t WEB_domains_list_size;						/**< Trusted Web Domains list size */
	sservice_size_t number_of_owners;							/**<  */
	char salt[AES128_SALT_SIZE];								/*salt for extra key owner*/
}sealed_data_header_t ;


typedef char utf8char;

typedef enum
{
	SECURE_TRANSPORT_INTEGER=0,
	SECURE_TRANSPORT_REAL,
	SECURE_TRANSPORT_STRING,
	SECURE_TRANSPORT_OBJECT,
	SECURE_TRANSPORT_BOOLEAN
} sservice_secure_transport_data_type_t;

typedef enum 
{
	REGULAR_PLAINTEXT=0,
	SECURE_DATA_PLAINTEXT_DECORATED,
	SECURE_DATA_PLAINTEXT_UNDECORATED,
	SECURE_DATA_SEALED_DATA_DECORATED,
	SECURE_DATA_SEALED_DATA_UNDECORATED
} sservice_secure_transport_format_t;

typedef enum
{
	MIN_CONTENT_TYPE=0,
	GENERIC=0,
	JSON=1,
	MAX_CONTENT_TYPE=1
} sservice_secure_transport_content_type_t;




typedef enum
{
	INVALID_HTTP_METHOD =-1,
	MIN_HTTP_METHODS	= 0,
	HTTP_METHOD_GET		= 0,
	HTTP_METHOD_POST	= 1,
	HTTP_METHOD_PUT		= 2,
    HTTP_METHOD_DELETE	= 3,
    HTTP_METHOD_HEAD	= 4,
    HTTP_METHOD_OPTIONS = 5,
	MAX_HTTP_METHODS	= 5, 

}  sservice_http_method_t;

typedef enum
{
	MINIMUM_HEADER_TYPE_HEADER=0,
	HTTP_HEADER=0,
	SECURE_DATA_HEADER=1,
	MAXIMUM_HEADER_TYPE_HEADER=1
}sservice_secure_transport_header_type_t;

typedef struct{
	char* key;
	char* value;
	sservice_secure_transport_header_type_t header_type;
} sservice_http_header_t;



typedef enum
{
       TRANSPORT_STATE_IDLE = 0,
       TRANSPORT_STATE_WAITING_FOR_RESPONSE = 1,
       TRANSPORT_STATE_RESPONSE_READY = 2
} sservice_secure_transport_state_t;

typedef uint32_t sservice_secure_transport_timeout_t;

typedef enum 
{
	 SSERVICE_OBJTYPE_SECURE_DATA=0,
     SSERVICE_OBJTYPE_SECURE_TRANSPORT,
	 SSERVICE_OBJTYPE_PERSONA
}sservice_object_type_t;


typedef enum {
	CONFIG_ID_APP_PATH = 0x100,
	CONFIG_ID_LOCAL_PATH,
	CONFIG_ID_APP_ID,
	CONFIG_ID_HARDWARE_ID, 
	CONFIG_ID_OS_VERSION,
	CONFIG_ID_WHITELIST_ACCESS_MIN = 0xD000,
	CONFIG_ID_WHITELIST_ACCESS_MAX = 0xDFFF,
	CONFIG_ID_WHITELIST_SUBDOMAINS_MIN = 0xE000,
	CONFIG_ID_WHITELIST_SUBDOMAINS_MAX = 0xEFFF,
	CONFIG_ID_CACERT_MIN = 0xF000,
	CONFIG_ID_CACERT_MAX = 0xFF00,
}config_id_enum_t ;


#ifdef _MSC_VER
#pragma pack( pop)
#endif


#endif
