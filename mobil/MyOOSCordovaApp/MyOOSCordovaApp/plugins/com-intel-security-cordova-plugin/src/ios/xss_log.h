#ifndef SSERVICE_LOGGER_H
#define SSERVICE_LOGGER_H
	
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
	@brief Logger system header.
*/

#include "xss_types.h"
#include "xss_error.h"

/**
* @enum log_target_t defines Types of log messages
*/
#define LOG_ERROR_VAL (0)
#define LOG_WARNING_VAL (1)
#define LOG_INFO_VAL (2)
#define LOG_ALL_VAL ( 0xff )
typedef enum 
{
	LOG_ERROR = LOG_ERROR_VAL,		/**< error. Operation is not successfull; Continue to run */
	LOG_WARNING = LOG_WARNING_VAL,	/**< Warning. Operation is finished successfully, but there is something to notify (Old keys?) */
	LOG_INFO = LOG_INFO_VAL,		/**< Information to developer; */
	LOG_ALL = LOG_ALL_VAL,			/**< Cannot be passed to log, it is just set for Log filtering: Print ALL logs */
} sservice_log_level_t ;


#define LOG_SOURCE_JS_VAL (2)
#define LOG_SOURCE_BRIDGE_VAL (1)
#define LOG_SOURCE_RUNTIME_VAL (0)

typedef enum
{
    LOG_SOURCE_JS=LOG_SOURCE_JS_VAL,
    LOG_SOURCE_BRIDGE = LOG_SOURCE_BRIDGE_VAL,
    LOG_SOURCE_RUNTIME = LOG_SOURCE_RUNTIME_VAL,
} sservice_log_source_t ;

#endif
