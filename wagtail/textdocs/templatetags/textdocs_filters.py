from django import template

register = template.Library()

@register.filter
def get_contents_related_slug( block, table_of_contents ):
    if block.id not in table_of_contents:
        return ''
    
    return table_of_contents[block.id].get( 'slug', '' )