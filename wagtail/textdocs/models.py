from django.db import models
from wagtail.models import Page
from wagtail.fields import StreamField, RichTextField
from wagtail.admin.panels import FieldPanel
from wagtail import blocks
from django.utils.text import slugify
import random

class TextDocPage( Page ):
    
    # Fields
    intro = RichTextField( blank = True )
    body = StreamField([
        ( 'section_heading', blocks.CharBlock() ),
        ( 'paragraph', blocks.RichTextBlock()),
    ], use_json_field=True)

    show_table_of_contents = models.BooleanField( default = True )

    date_created = models.DateField( auto_now_add = True )
    date_updated = models.DateField( auto_now = True )

    # Admin
    content_panels = Page.content_panels + [
        FieldPanel( 'intro' ),
        FieldPanel( 'body' ),
    ]

    promote_panels = Page.promote_panels + [
        FieldPanel('show_table_of_contents'),
    ]


    def get_context(self, request, *args, **kwargs):
        context = super().get_context(request, *args, **kwargs)

        contents = {}
        for block in self.body:
            if block.block_type != 'section_heading': 
                continue 

            section_slug = slugify( block.value, False )

            if section_slug in contents:
                section_slug += random.randrange( 10, 99 )

            contents[block.id] = {
                "slug": slugify( block.value ),
                "title": block.value,
            }

        context["table_of_contents"] = contents
        return context