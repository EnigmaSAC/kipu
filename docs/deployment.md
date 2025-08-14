# Deployment and Localization

## Disable Remote Integrations

Administrators can disable outbound connections to the Akaunting API by clearing the `akaunting_api_url` setting. This is useful for local installations or environments without internet access.

Edit your `.env` file and set:

```
AKAUNTING_API_URL=
```

When this value is empty, the application will skip all requests made through `SiteApi`, effectively disabling remote integrations. The default value is `https://api.akaunting.com/`.

