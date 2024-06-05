from django.db import models
from wagtail.models import Page 


class TutorialTopicPage( Page ):
    page_description = 'Used to group tutorials into topics'

    parent_page_types = [
        'tutorials.TutorialIndexPage',
    ]

    def get_context( self, request, *args, **kwargs ):
        context = super().get_context(request, *args, **kwargs)
        
        context['posts'] = self.get_children().specific()

        return context

class TutorialIndexPage( Page ):
    page_description = 'Index page for tutorials and help pages'


    def get_context(self, request, *args, **kwargs):
        context = super().get_context(request, *args, **kwargs)
        
        context['topics'] = self.get_children().type( TutorialTopicPage )
        context['posts'] = self.get_children().not_type( TutorialTopicPage ).specific()

        return context 