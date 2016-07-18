# Batmage_Xmldebug

A simple module for Magento 1.x to help you with debugging XML layouts.

Brought to you by Batmage!

## Installation

The easiest way is using [modman](https://github.com/colinmollenhour/modman), if you can:

```bash
modman clone https://github.com/robbieaverill/Batmage_Xmldebug.git
```

Clear your cache before use.

## Usage

From any frontend controller route, add the `?debugxml=1` query string parameter.

## Warning!

Please do not deploy this to a production Magento environment, and do not commit this code into a repository that may
make its way to a production environment!
