from .base import *
import os

DEBUG = False

SECRET_KEY = os.getenv( 'SECRET_KEY' )

ALLOWED_HOSTS = [ 'aktulibre.eu', 'wagtail.aktulibre.eu' ]
CSRF_TRUSTED_ORIGINS = ['https://aktulibre.eu', 'https://wagtail.aktulibre.eu']

DATABASES = {
   "default": {
       "ENGINE": "django.db.backends.mysql",
       "NAME": os.getenv( "DB_NAME" ),
       "HOST": os.getenv( "DB_HOST" ),
       "USER": os.getenv( "DB_USER" ),
       "PASSWORD": os.getenv( "DB_PASSWORD" ),

   }
}

LOGGING = {
    "version": 1,
    "disable_existing_loggers": False,
    "formatters": {
        "verbose": {
            "format": "{name} {levelname} {asctime} {module}: {message}",
            "style": "{"
        }
    },
    "handlers": {
        "file": {
            "level": "ERROR",
            "formatter": "verbose",
            "class": "logging.RotatingFileHandler",
            "filename": os.path.join( BASE_DIR, 'logs', 'error.log' ),
            "maxBytes": 1024 * 1024 * 10,
            "backupCount": 5,
        },
    },
    "loggers": {
        "django": {
            "handlers": ["file"],
            "level": "ERROR",
            "propagate": True,
        },
    },
}

try:
    from .local import *
except ImportError:
    pass
