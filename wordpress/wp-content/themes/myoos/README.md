# CampusPress Flex Theme

Website: [https://campuspress.com/wordpress-theme-for-schools/](https://campuspress.com/wordpress-theme-for-schools/)

## About

New, flexible theme from CampusPress for accessible, fast, and an easy-to-navigate site. Perfect for your new school, district, department or simple site.

## License
CampusPress Flex WordPress Theme, Copyright 2020 CampusPress.

CampusPress Flex is distributed under the terms of the GNU GPL.

http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html

## Changelog
See [changelog](CHANGELOG.md)

## Using Dependencies
- Make sure you have installed Node.js and Browser-Sync (optional) on your computer globally
- Then open your terminal and browse to the location of your CampusPress Flex copy
- Run: `$ npm install`

### Running
To work with and compile your Sass files on the fly start:

- `$ gulp watch`

Or, to run with Browser-Sync:

- First change the browser-sync options to reflect your environment in the file `/gulpconfig.json` in the beginning of the file:
```javascript
{
    "browserSyncOptions" : {
        "proxy": "localhost/theme_test/", // <----- CHANGE HERE
        "notify": false
    },
    ...
};
```
- then run: `$ gulp watch-bs`

## Child Themes
Theme is still in active development and some child theme breaking changes can happen. 

## Licenses & Credits
- Underscores: http://underscores.me/ | GNU GPL 
- UnderStrap: http://understrap.com | GNU GPL
- WP Bootstrap Navwalker by Edward McIntyre: https://github.com/twittem/wp-bootstrap-navwalker | GNU GPL
- TwentyTwenty: https://github.com/WordPress/twentytwenty | GNU GPL
- Kirki: https://kirki.org/ | MIT
- WP Menu Icons: https://wordpress.org/plugins/wp-menu-icons/ | GNU GPL
- Bootstrap: http://getbootstrap.com | https://github.com/twbs/bootstrap/blob/master/LICENSE (Code licensed under MIT documentation under CC BY 3.0.)
- jQuery: https://jquery.org | MIT
- AOS - Animate on scroll library: http://michalsnik.github.io/aos/ | MIT
- JavaScript Cookie v2.2.0: https://github.com/js-cookie/js-cookie | MIT
- css-vars-ponyfill: https://jhildenbiddle.github.io/css-vars-ponyfill/#/ | MIT
- IcoMoon - Free: https://icomoon.io/ | GNU GPL
- 60 Vicons: https://dribbble.com/shots/1663443-60-Vicons-Free-Icon-Set | Custom
- Feather: https://feathericons.com/ | MIT
- Material Icons: https://material.io/resources/icons/?style=baseline | APACHE LICENSE, VERSION 2.0
- Font Awesome: https://github.com/FortAwesome/Font-Awesome | https://github.com/FortAwesome/Font-Awesome#license SIL OLF
- Amstelvar Font: https://github.com/TypeNetwork/Amstelvar | 
- Commissioner Font: https://github.com/kosbarts/Commissioner | 
- Epilogue Font: https://fontesk.com/epilogue-typeface/ | 
- Gelasio Font: https://github.com/SorkinType/Gelasio | SIL OLF
- Hepta Slab: https://github.com/mjlagattuta/Hepta-Slab | SIL OLF
- Inter Font:  https://rsms.me/inter/ | SIL OLF
- Lexend: https://github.com/ThomasJockin/lexend | SIL OLF
- Manrop: https://github.com/sharanda/manrope | SIL OLF
- Merriweather: https://github.com/EbenSorkin/Merriweather | SIL OLF
- Mohave: https://github.com/tokotype/Mohave-Typefaces | SIL OLF
- Petrona Font: https://github.com/RingoSeeber/Petrona | SIL OLF
- Public Sans: https://github.com/uswds/public-sans | SIL OLF
- Russolo: https://github.com/laerm0/Russolo | SIL OLF
- Space Grotesk: https://github.com/floriankarsten/space-grotesk | SIL OLF
- Urbanist: https://github.com/coreywho/Urbanist | SIL OLF