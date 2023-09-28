from .base import *
import os

DEBUG = False

SECRET_KEY = os.getenv( 'SECRET_KEY' )

DATABASES = {
   "default": {
       "ENGINE": "django.db.backends.mysql",
       "NAME": os.getenv( "DB_NAME" ),
       "HOST": os.getenv( "DB_HOST" ),
       "USER": os.getenv( "DB_USER" ),
       "PASSWORD": os.getenv( "DB_PASSWORD" ),

   }
}

try:
    from .local import *
except ImportError:
    pass
