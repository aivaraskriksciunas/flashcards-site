from .base import *
import os

# SECURITY WARNING: don't run with debug turned on in production!
DEBUG = True

# SECURITY WARNING: keep the secret key used in production secret!
SECRET_KEY = "django-insecure-z=&!7wt4e2nmro5pep9rq0%_@(&z$@^bvguw4y-v)c&rsm!h1z"

# SECURITY WARNING: define the correct hosts in production!
ALLOWED_HOSTS = ["*"]

EMAIL_BACKEND = "django.core.mail.backends.console.EmailBackend"

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
