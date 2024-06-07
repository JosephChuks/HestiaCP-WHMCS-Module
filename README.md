# HestiaCP-WHMCS-Module
HestiaCP WMHCS Module<br>
The module uses the recommended `access/secret keys` authentication.

# Supported Functions
Create Account (user and domain)<br>
Suspend Account.<br>
Unsuspend Account.<br>
Change Password.<br>
Change Packages.<br>
Terminate Account.<br>
Install LetsEncrypt SSL.

# Prerequisites
Your WHMCS server must be open to your hestiacp server port (8083) for outgoing requests.<br>
Go to `Server settings` > `Security` > `System` in your hestia panel:<br>
Enable API access => `Enabled for all users`.<br>
Enable legacy API access => `No`.<br>
Allowed IP addresses for API => `0.0.0.0`.<br>
Enable LetsEncrypt Installation command for admin access keys by adding `v-add-letsencrypt-domain` to the list of commands here: `/usr/local/hestia/data/api/billing`


# Installation
Download code and upload to `modules/servers/hestia`. make sure `hestia` directory exists, create one if not.

# How to set up server details
In WHMCS server settings:<br>
Module => `HestiaCP`.<br>
Username => `admin username` (optional).<br>
Password => `admin password` (optional).<br>
Access Hash => `access_key`:`secret_key`. (required)
