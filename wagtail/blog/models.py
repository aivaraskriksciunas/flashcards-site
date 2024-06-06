from django.db import models
from wagtail.models import Page
from wagtail.fields import RichTextField, StreamField
from wagtail import blocks
from wagtail.images.blocks import ImageChooserBlock
from wagtail.admin.panels import FieldPanel, MultiFieldPanel, InlinePanel
from modelcluster.fields import ParentalKey
from modelcluster.contrib.taggit import ClusterTaggableManager
from taggit.models import TaggedItemBase

class BlogIndexPage( Page ):
    page_description = 'This page is the index for the blog' 

    def get_context( self, request, *args, **kwargs ):
        context = super().get_context(request, *args, **kwargs)

        blog_entries = BlogPage.objects.child_of( self ).live()

        # Filter by tag
        tag = request.GET.get('tag')
        if tag:
            blog_entries = blog_entries.filter( tags__name = tag.lower() )
            pass

        context['posts'] = blog_entries.specific()
        context['tag_filter'] = tag

        return context

class BlogPageTag( TaggedItemBase ):
    content_object = ParentalKey( 'blog.BlogPage', on_delete=models.CASCADE, related_name='tagged_items' )


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

    tags = ClusterTaggableManager( through = BlogPageTag, blank = True )

    # Content
    content_panels = Page.content_panels + [
        FieldPanel( 'intro' ),
        FieldPanel( 'body' ),
    ]

    # Promote
    promote_panels = Page.promote_panels + [
        FieldPanel( 'tags' ),
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

    def get_blog_index_page( self ):
        return Page.objects.type( BlogIndexPage ).live().first()


