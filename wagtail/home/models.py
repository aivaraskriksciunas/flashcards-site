from django.db import models

from wagtail.models import Page
from wagtail.fields import RichTextField
from wagtail.admin.panels import FieldPanel

class HomePage(Page):
    title = "Front page"
    hero_heading = models.CharField( 
        max_length = 80, 
        default = "Your new study companion" 
    )
    hero_subtitle = models.CharField( 
        max_length = 250, 
        default = "Rapid, flexible and simple as 2+2. Learn new material using the spaced-repetition approach." 
    )

    hero_image = models.ForeignKey(
        'wagtailimages.Image',
        null=True,
        blank=True,
        on_delete=models.SET_NULL,
        related_name='+'
    )

    content_panels = Page.content_panels + [
        FieldPanel( 'hero_heading' ),
        FieldPanel( 'hero_subtitle' ),
        FieldPanel( 'hero_image' ),
    ]
