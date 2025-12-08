# Joomla Frontend Login Control Plugin

Joomla system plugin that lets site owners control or disable unused frontend login and registration entry points.

This helps:

- Reduce brute-force exposure by limiting public login URLs.
- Avoid confusing non-admin users with unused login/registration links.
- Keep legacy frontend login paths under control on sites that use backend-only access.

**Compatibility:** Joomla 4.x and Joomla 5.x  

## Structure

Source code lives in:

`plg_system_loginremover/`

You can place this folder under `plugins/system/` in a Joomla installation for development.

Key files:

- `plg_system_loginremover/loginremover.xml` – plugin manifest
- `plg_system_loginremover/services/provider.php` – service provider (Joomla 4+)
- `plg_system_loginremover/src/Extension/LoginRemover.php` – main plugin class
- `plg_system_loginremover/language/en-GB/*.ini` – language strings

## Installation

1. Download the plugin ZIP from your vendor site or this repository's Releases page (when available).
2. In Joomla admin, go to **System → Install → Extensions**.
3. Upload and install the ZIP.
4. Go to **System → Plugins**, search for the plugin (e.g. "Login Remover"), and enable it.
5. Configure which frontend login/registration routes should remain active.

## About

Built and maintained by Daniel McNulty (Cane Tree Corp).  
More info and commercial support: https://canetree.com
