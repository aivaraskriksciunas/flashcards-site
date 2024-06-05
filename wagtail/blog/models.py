from django.db import models
from wagtail.models import Page
from wagtail.fields import RichTextField, StreamField
from wagtail import blocks
from wagtail.images.blocks import ImageChooserBlock
from wagtail.admin.panels import FieldPanel, MultiFieldPanel, InlinePanel

class BlogIndexPage( Page ):
    page_description = 'This page is the index for the blog' 

    def get_context( self, request, *args, **kwargs ):
        context = super().get_context(request, *args, **kwargs)

        context['posts'] = BlogIndexPage.objects.live().order_by( '-last_published_at' )
        return context

class BlogPage( Page ):
    
    # Fields 
    intro = RichTextField( blank = True )
    body = StreamField([
        ( 'section_heading', blocks.CharBlock() ),
        ( 'paragraph', blocks.RichTextBlock()),
        ( 'image', ImageChooserBlock() ),
    ], use_json_field = True )

    date_created = models.DateTimeField( auto_now_add = True )
    date_updated = models.DateTimeField( auto_now = True )

    # Content
    content_panels = Page.content_panels + [
        FieldPanel( 'intro' ),
        FieldPanel( 'body' ),
    ]

    # Parent pages
    parent_page_types = [
        'blog.BlogIndexPage',
        'tutorials.TutorialIndexPage',
        'tutorials.TutorialTopicPage',
    ]


    def get_url_parts( self, *args, **kwargs ):
        url_parts = super().get_url_parts( *args, **kwargs )

        if not url_parts:
            return None 
        
        ( site_id, root_url, page_path ) = url_parts 
        return ( site_id, root_url, page_path )
