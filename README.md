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

**Registrar evento**
---

Permite crear un nuevo evento, asociado al usuario que ha ingresado al sistema.

* **URL**

  `/api/v1/event/register`

* **Método**

  `POST`

* **Parámetros**

    - `name=<nombre del evento>`  
    - `description=<descripción del evento>`
    - `location=<lugar donde se llevará a cabo el evento>`
    
    El parámetro `location` es opcional, los otros dos son obligatorios.

* **Encabezado:**

    - `Accept: application/json`
    - `Authorization: Bearer <token>`

* **Respuesta correcta:**

    - **Status:** `200 OK`
    - **Contenido:** `{
        "status": 1,
        "message": "Event has been registered",
        "event_id": <identificador del nuevo evento>
      }`

* **Respuesta errónea:**

    - **Status:** `401 Unauthorized`
    - **Contenido:** `{"message":"Unauthenticated."}`

**Listar todos los evento**
---

Permite conocer todos los eventos existentes en la base de datos bajo la propiedad
del usuario actualmente logueado en el sistema.

* **URL**

  `/api/v1/event/list`

* **Método**

  `GET`

* **Parámetros**

    Ninguno
  
* **Encabezado:**

    - `Accept: application/json`
    - `Authorization: Bearer <token>`

* **Respuesta correcta:**

    - **Status:** `200 OK`
    - **Contenido:** 
      ```json
        {
        "status": 1,
        "message": "Events",
        "data": [
        {
        "id": 1,
        "name": "Encuentro Nacional De Investigadores",
        "description": "Vamos a realizar un evento interesante lleno de cosas aun mas interesantes. Sean todos bienvenidos.",
        "location": "Bogotá, Colombia",
        "user_id": "5"
        },
        {
        "id": 2,
        "name": "Reunión Nacional De Semilleros De Colombia",
        "description": "Este es el encuentro más importante de todos los tiempos. No se que vamos a hacer ahora.",
        "location": "Cartagena, Colombia",
        "user_id": "5"
        }
        ],
        "organization": "Universidad Ean"
        }      
      ```
* **Respuesta errónea:**

    - **Status:** `401 Unauthorized`
    - **Contenido:** `{"message":"Unauthenticated."}`
