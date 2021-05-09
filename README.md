# Proyecto Dahlia - Documentación del REST API

Este es la documentación del API para el proyecto Dahlia. En este proyecto vamos
a construir una aplicación web que permite la gestión de eventos para la Universidad
Ean.

## Instalación

Copiar la carpeta del proyecto dentro de una carpeta del servidor web que estamos
utilizando. Esta versión trabaja con PHP Laravel 8.0 y una base de datos Sqlite 3.0,
por lo tanto hay que configurar esta base de datos antes de utilizar los servicios
del API. En el archivo `.env` en la raíz del proyecto, localizar la entrada denominada
`DB_DATABASE` y escribir la ruta al lugar en el computado donde se encuentra el
archivo con la base de datos.

# REST API

El API hasta ahora es la siguiente:

**Login**
---

  Ingresar al sistema y obtener el token de seguridad.

* **URL**

  `/api/login`

* **Método:**
 
  `POST`

* **Parámetros:**

  - `email=<correo usuario>`
  - `password=<clave del usuario>`
  - `name=<plataforma>`
    
* **Encabezado:**

  - `Accept: application/json`
    
* **Respuesta correcta:**

  - **Status:** `200 OK`
  - **Contenido:** `{"token":"2|XoHXdcqnh1B6MhSbNQuszwyFnyUKENAQjz62dtna","message":"Success"}`
    
* **Respuesta errónea:**

  - **Status:** `401 Unauthorized`
  - **Contenido:** `{"message":"Unauthorized"}`

**Logout**
---

Permite eliminar el token e impide que el usuario utilice las operaciones del sistema.
Sin embargo para poder hacer `logout` el usuario debe tener un token válido.

* **URL**

  `/api/logout`

* **Método:**

  `GET`

* **Parámetros:**

    No hay 

* **Encabezado:**

    - `Accept: application/json`
    - `Authorization: Bearer <token>`

* **Respuesta correcta:**

    - **Status:** `200 OK`
    - **Contenido:** `{"message":"Success"}`

* **Respuesta errónea:**

    - **Status:** `401 Unauthorized`
    - **Contenido:** `{"message":"Unauthenticated."}`
