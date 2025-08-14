# Despliegue y localización

## Desactivar integraciones remotas

Los administradores pueden deshabilitar las conexiones salientes a la API de Akaunting limpiando el ajuste `akaunting_api_url`. Esto es útil para instalaciones locales o entornos sin acceso a Internet.

Edite su archivo `.env` y establezca:

```
AKAUNTING_API_URL=
```

Cuando este valor está vacío, la aplicación omitirá todas las solicitudes realizadas a través de `SiteApi`, desactivando efectivamente las integraciones remotas. El valor predeterminado es `https://api.akaunting.com/`.

