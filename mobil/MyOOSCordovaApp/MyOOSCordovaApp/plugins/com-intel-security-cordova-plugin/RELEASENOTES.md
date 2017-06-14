2.0.3 (December, 2016)
	Updated plugin's custom hook for Windows platforms to support build from Visual Studio 2015 & Visual Studio 2017
	Supported Platforms: Android, iOS, Windows MUI 8/8.1/10
	
	Known issues:
	- Creating big SecureData items returns 'Internal Error' on Windows and crashes on iOS & Android instead of returning 'Memory allocation failure' error.
	
	The plugin was tested on:
	- Android Intel and ARM devices
    - iPhone ARM device
	- Windows 8 Intel (x86, x64) devices
    - Windows 8.1  ARM and Intel (x86, x64) devices
    - Windows 10 Intel (x86, x64) devices
	
2.0.2 (July, 2016)
	Updated Cordova plugin's ID to align with npmjs ID.	
	Supported Platforms: Android, iOS, Windows MUI 8/8.1/10
	
	Known issues:
	- Creating big SecureData items returns 'Internal Error' on Windows and crashes on iOS & Android instead of returning 'Memory allocation failure' error.
	
	The plugin was tested on:
	- Android Intel and ARM devices
    - iPhone ARM device
	- Windows 8 Intel (x86, x64) devices
    - Windows 8.1  ARM and Intel (x86, x64) devices
    - Windows 10 Intel (x86, x64) devices
	
2.0.1 (July, 2016)
	Updated OpenSSL to version 1.0.2h (iOS version is 1.0.2g)
	Updated libcurl to version 7.49.1 (iOS version is 7.47.1)	
	Supported Platforms: Android, iOS, Windows MUI 8/8.1/10
	
	Known issues:
	- Creating big SecureData items returns 'Internal Error' on Windows and crashes on iOS & Android instead of returning 'Memory allocation failure' error.
	
	The plugin was tested on:
	- Android Intel and ARM devices
    - iPhone ARM device
	- Windows 8 Intel (x86, x64) devices
    - Windows 8.1  ARM and Intel (x86, x64) devices
    - Windows 10 Intel (x86, x64) devices

2.0.0 (April, 2016)			
	Move to promises API (still support callback API)
	Updated plugin name to 'com-intel-security' to be aligned with NPMJS style
	Updated OpenSSL to version 1.0.2g
		
	Supported Platforms: Android, iOS, Windows MUI 8/8.1/10
	
	The plugin was tested on:
	- Android Intel and ARM devices
    - iPhone ARM device
	- Windows 8 Intel (x86, x64) devices
    - Windows 8.1  ARM and Intel (x86, x64) devices
    - Windows 10 Intel (x86, x64) devices

1.4.1 (February, 2016)
	Added support for bitcode optimization in iOS
	Support Cordova hook- no need to execute manually chooseArch_Windows.js to attach the correct binaries for the architecture (x86/x64/arm)
	Fixed build failure in Cordova-iOS debug configuration
	Added Android x64 (64 bit) support
	Updated OpenSSL to version 1.0.2f
		
	Supported Platforms: Android, iOS, Windows 8/8.1/10
	
	The plugin was tested on:
	- Android Intel and ARM devices
    - iPhone ARM device
	- Windows 8 Intel (x86, x64) devices
    - Windows 8.1  ARM and Intel (x86, x64) devices
    - Windows 10 Intel (x86, x64) devices

1.4.0 (December, 2015)
    Added support for Windows 10
    Secure Data createFromData and getWebOwners API webOwners parameter changed to an array of strings 
    Secure Transport abort API added
    Secure Transport setHeaders API replaces setHeaderValue API
	
	Supported Platforms: Android, iOS, Windows 8/8.1/10
	
	The plugin was tested on:
	- Android Intel and ARM devices
    - iPhone ARM device
	- Windows 8 Intel (x86, x64) devices
    - Windows 8.1  ARM and Intel (x86, x64) devices
    - Windows 10 Intel (x86, x64) devices
    
1.3.0 (July, 2015)

	Added support for Cordova 5.1.1
	Added support for Windows 8.1
	Added support for public key pinning on iOS.
	Secure Storage API change: "writeSecureData" changed to "write"
	
	Supported Platforms: Android, iOS, Windows 8/8.1
	
	The plugin was tested on:
	- Android ARM and Intel devices
    - iPhone ARM device
	- Windows 8 Intel (x86, x64) devices
    - Windows 8.1  ARM and Intel (x86, x64) devices
	
	Known limitations:
	- public key pinning not supported on Windows 8/8.1 .
	
1.2.0 (June, 2015)

	Added Secure Transport API.
	Supported Platforms: Android, iOS, Windows 8
	
	The plugin was tested on:
	- Android ARM and Intel devices
    - iPhone ARM device
    - Windows 8, ARM and Intel (x86, x64) devices
	
	Known limitations:
	- public key pinning not supported on Windows and iOS.
	

1.1.1 (April, 2015)

     Directory restructuring + fix plugin.xml
     Supported Platforms: Android, iOS, Windows 8
	
     The plugin was tested on:
     - Android ARM and Intel devices
     - iPhone ARM device
     - Windows 8, ARM and Intel (x86, x64) devices
	 
1.1.0 (March, 2015)

    Added extra key support and changeExtraKey API
	
    Supported Platforms: android, ios, win8. 

    The plugin was tested on:
    - Android Arm and Intel devices.
    - iPhone Arm device.
    - Win8 Arm and Intel (x86, x64) devices.

1.0.5 (February, 2015)

    Cordova 4.1.2/windows platform alignment

    Supported Platforms: android, ios, win8 and wp8. 

    The plugin was tested on:
    - Win8 Intel (x86, x64) devices.

1.0.4 (December, 2014)

    Updated OpenSSL to version 1.0.1j

    Supported Platforms: android, ios, win8 and wp8. 

    The plugin was tested on:
    - Android Arm and Intel devices.
    - iPhone Arm device.
    - Win8 Arm and Intel (x86, x64) devices.
    - WP8 Arm device.

1.0.3 (August, 2014)

    Fixed copyrights.

    Supported Platforms: android, ios, win8 and wp8. 

    The plugin was tested on:
    - Android Arm and Intel devices.
    - iPhone Arm device.
    - Win8 Arm and Intel (x86, x64) devices.
    - WP8 Arm device.

1.0.2 (August, 2014)

    Added copyrights, Fixed issue in Win8 certificate testing (App Certification Kit failure),
    Updated the chooseArch.js and AddSealRockPlugin.js scripts for win8.

    Supported Platforms: android, ios, win8 and wp8. 

    The plugin was tested on:
    - Android Arm and Intel devices.
    - iPhone Arm device.
    - Win8 Arm and Intel (x86, x64) devices.
    - WP8 Arm device.

1.0.1 (July, 2014)

    Fixed bug in iOS (getTag API failed in case that tag is an empty string)    

    Supported Platforms: android, ios, win8 and wp8. 

    The plugin was tested on:
    - Android emulator, Arm and Intel devices.
    - iPhone simulator and Arm device.
    - Win8 emulator, Arm and Intel (x86, x64) devices.
    - WP8 emulator and Arm device.

1.0.0 (July, 2014)

    First version of security services API cordova plugin 
    Supported Platforms: android, ios, win8 and wp8. 

    The plugin was tested on:
    - Android emulator, Arm and Intel devices.
    - iPhone simulator and Arm device.
    - Win8 emulator, Arm and Intel (x86, x64) devices.
    - WP8 emulator and Arm device.

