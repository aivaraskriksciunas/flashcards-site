from wagtail.images.blocks import ImageChooserBlock
from wagtail.blocks import StructBlock, CharBlock

class ParagraphImageBlock( StructBlock ):
    image = ImageChooserBlock( required = False )
    title = CharBlock( required = False, max_length = 200 )
    alt = CharBlock( required = False, max_length = 300, search_index = False )
    source = CharBlock( required = False, max_length = 300, search_index = False )
    source_url = CharBlock( required = False, max_length = 300, search_index = False )

    class Meta:
        template = 'blocks/paragraph-image-block.html'
        search_index = False
        icon = 'image'

