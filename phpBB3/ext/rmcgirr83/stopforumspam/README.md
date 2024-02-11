# Stop Forum Spam

phpBB Stop Forum Spam extension (requires phpBB version 3.3 or higher)

Extension will query the stop forum spam database on registration and posting (for guests only) and deny the post and or registration to go through if found. Will log an entry in the ACP if so set.

[![Build Status](https://github.com/rmcgirr83/stopforumspam/workflows/Tests/badge.svg)](https://github.com/rmcgirr83/stopforumspam/actions)

## Installation

### 1. clone
Clone (or download and move) the repository into the folder ext/rmcgirr83/stopforumspam:

```
cd phpBB3
git clone https://github.com/rmcgirr83/stopforumspam.git ext/rmcgirr83/stopforumspam/
```

### 2. activate
Go to admin panel -> tab customise -> Manage extensions -> enable Stop Forum Spam

Within the Admin panel visit the Extensions tab and within choose the settings for the extension.

## Update instructions:
1. Go to your phpBB-Board > Admin Control Panel > Customise > Manage extensions > Stop Forum Spam: disable
2. Delete all files of the extension from ext/rmcgirr83/stopforumspam
3. Upload all the new files to the same location
4. Go to your phpBB-Board > Admin Control Panel > Customise > Manage extensions > Stop Forum Spam: enable
5. Purge the board cache
