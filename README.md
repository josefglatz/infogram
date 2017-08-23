# TYPO3 CMS Extension `infogram`

> This extension implements the most important feature of infogr.am: Let TYPO3 backend editors add an infogram content element via infogr.am API (TYPO3 backend user can select an existing infogram right within the flexforms of the content element plugin)

![Backend Screenshot](./Documentation/Images/ext-infogram-backend-v1.png "Extension Demo v1.x")

## Requirements

- TYPO3 CMS 8.7 LTS
- infogr.am valid user account
- infogr.am API key
- infogr.am API secret
- infogr.am API username
- License: GPL 3

## Installation

### Installation using Composer

The recommended way to install the extension is by using [Composer](https://getcomposer.org/). In your Composer based TYPO3 project root, just do `composer require josefglatz/infogram`

### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the TYPO3 CMS extension manager module or directly via [typo3.org](https://typo3.org/extensions/repository/view/infogram).

## Manual

Please take a look at the full manual inside the Documentation directory or rendered at https://docs.typo3.org/typo3cms/extensions/infogram/.

## WIP

- [ ] Documentation
- [x] non composer mode support (only works with export script)
- [x] Extension Icon
- [ ] API integration
- [x] Register Plugin
- [x] Flexforms
- [ ] Flexform logic
- [x] Register Icon `ext-infogram-wizard-icon`
- [x] Design Icon
- [ ] Fine tune LLL
- [x] NCEW
- [x] NCEW registration
- [x] TCA
- [ ] PageLayoutViewHook: Plugin summary (incl. refactoring)
- [ ] Finalize Exception Handling
- [x] ExtensionConfiguration class
- [x] Github tags
- [ ] Github first public release
- [ ] Packagist registration
- [ ] Fluid Template ShowAction
- [ ] Add cacheTag logic (like ext:news does it)
- [ ] Add backend report "Is infogram.com API available; are credentials correct?"
- [ ] Find way to add external thumbnails for flexform select field
