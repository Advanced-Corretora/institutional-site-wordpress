(function () {
  const { registerBlockType } = wp.blocks;
  const { __ } = wp.i18n;
  const {
    useBlockProps,
    InspectorControls,
    BlockControls,
    AlignmentToolbar,
    RichText,
    PanelColorSettings,
    MediaUpload,
    MediaUploadCheck,
  } = wp.blockEditor;
  const { PanelBody, Button, TextControl, ToolbarGroup } = wp.components;
  const { createElement: el, Fragment } = wp.element;

  // console.log('Seção CTA: Iniciando registro do bloco secao-cta');
  // console.log('wp object:', wp);

  registerBlockType('advanced-corretora/secao-cta', {
    edit: ({ attributes, setAttributes }) => {
      const {
        title,
        subtitle,
        ctaText,
        ctaLink,
        image,
        imageId,
        textAlignment,
        backgroundColor,
        textColor,
        buttonColor,
        buttonTextColor,
        backgroundImage,
        backgroundImageId,
        overlayOpacity,
        overlayColor,
      } = attributes;

      const blockProps = useBlockProps({
        className: `secao-cta-block align-${textAlignment}`,
        style: {
          backgroundColor: backgroundColor,
          backgroundImage: backgroundImage ? `url(${backgroundImage.url})` : 'none',
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          backgroundRepeat: 'no-repeat',
          color: textColor,
          textAlign: textAlignment,
          position: 'relative',
        },
      });

      return el(
        Fragment,
        {},
        el(
          BlockControls,
          {},
          el(
            ToolbarGroup,
            {},
            el(AlignmentToolbar, {
              value: textAlignment,
              onChange: alignment => setAttributes({ textAlignment: alignment }),
            })
          )
        ),
        el(
          InspectorControls,
          {},
          el(PanelColorSettings, {
            title: __('Configurações de Cor', 'advanced-corretora'),
            colorSettings: [
              {
                value: backgroundColor,
                onChange: color => setAttributes({ backgroundColor: color }),
                label: __('Cor de Fundo', 'advanced-corretora'),
              },
              {
                value: textColor,
                onChange: color => setAttributes({ textColor: color }),
                label: __('Cor do Texto', 'advanced-corretora'),
              },
              {
                value: buttonColor,
                onChange: color => setAttributes({ buttonColor: color }),
                label: __('Cor do Botão', 'advanced-corretora'),
              },
              {
                value: buttonTextColor,
                onChange: color => setAttributes({ buttonTextColor: color }),
                label: __('Cor do Texto do Botão', 'advanced-corretora'),
              },
              {
                value: overlayColor,
                onChange: color => setAttributes({ overlayColor: color }),
                label: __('Cor do Overlay', 'advanced-corretora'),
              },
            ],
          }),
          el(
            PanelBody,
            { title: __('Imagem de Fundo', 'advanced-corretora'), initialOpen: false },
            el(
              MediaUploadCheck,
              {},
              el(MediaUpload, {
                onSelect: media =>
                  setAttributes({
                    backgroundImage: media,
                    backgroundImageId: media.id,
                  }),
                allowedTypes: ['image'],
                value: backgroundImageId,
                render: ({ open }) =>
                  el(
                    Button,
                    {
                      onClick: open,
                      isPrimary: !backgroundImage,
                      isSecondary: !!backgroundImage,
                    },
                    backgroundImage
                      ? __('Trocar Imagem de Fundo', 'advanced-corretora')
                      : __('Selecionar Imagem de Fundo', 'advanced-corretora')
                  ),
              })
            ),
            backgroundImage &&
              el(
                Button,
                {
                  onClick: () =>
                    setAttributes({
                      backgroundImage: null,
                      backgroundImageId: 0,
                    }),
                  isDestructive: true,
                  style: { marginTop: '10px' },
                },
                __('Remover Imagem de Fundo', 'advanced-corretora')
              ),
            backgroundImage &&
              el(TextControl, {
                label: __('Opacidade do Overlay (0-1)', 'advanced-corretora'),
                type: 'number',
                min: 0,
                max: 1,
                step: 0.1,
                value: overlayOpacity,
                onChange: value => setAttributes({ overlayOpacity: parseFloat(value) }),
                style: { marginTop: '15px' },
              })
          ),
          el(
            PanelBody,
            { title: __('Configurações do CTA', 'advanced-corretora'), initialOpen: true },
            el(TextControl, {
              label: __('Texto do Botão', 'advanced-corretora'),
              value: ctaText,
              onChange: value => setAttributes({ ctaText: value }),
            }),
            el(TextControl, {
              label: __('Link do Botão', 'advanced-corretora'),
              value: ctaLink,
              onChange: value => setAttributes({ ctaLink: value }),
            })
          ),
          el(
            PanelBody,
            { title: __('Imagem da Seção', 'advanced-corretora'), initialOpen: true },
            el(
              MediaUploadCheck,
              {},
              el(MediaUpload, {
                onSelect: media =>
                  setAttributes({
                    image: media,
                    imageId: media.id,
                  }),
                allowedTypes: ['image'],
                value: imageId,
                render: ({ open }) =>
                  el(
                    Button,
                    {
                      onClick: open,
                      isPrimary: !image,
                      isSecondary: !!image,
                    },
                    image
                      ? __('Trocar Imagem', 'advanced-corretora')
                      : __('Selecionar Imagem', 'advanced-corretora')
                  ),
              })
            ),
            image &&
              el(
                Button,
                {
                  onClick: () =>
                    setAttributes({
                      image: null,
                      imageId: 0,
                    }),
                  isDestructive: true,
                  style: { marginTop: '10px' },
                },
                __('Remover Imagem', 'advanced-corretora')
              )
          )
        ),
        el(
          'div',
          blockProps,
          backgroundImage &&
            el('div', {
              style: {
                position: 'absolute',
                top: 0,
                left: 0,
                right: 0,
                bottom: 0,
                backgroundColor: overlayColor,
                opacity: overlayOpacity,
                pointerEvents: 'none',
              },
            }),
          el(
            'div',
            { className: 'container' },
            el(
              'div',
              { className: 'cta-content' },
              el(
                'div',
                { className: 'cta-text' },
                el(RichText, {
                  tagName: 'h2',
                  placeholder: __('Digite o título...', 'advanced-corretora'),
                  value: title,
                  onChange: value => setAttributes({ title: value }),
                  style: {
                    fontSize: '2rem',
                    fontWeight: 'bold',
                    marginBottom: '1rem',
                    color: textColor,
                  },
                }),
                el(RichText, {
                  tagName: 'p',
                  placeholder: __('Digite o subtítulo...', 'advanced-corretora'),
                  value: subtitle,
                  onChange: value => setAttributes({ subtitle: value }),
                  style: {
                    fontSize: '1.2rem',
                    marginBottom: '2rem',
                    color: textColor,
                  },
                }),
                el(
                  'div',
                  { className: 'cta-button-wrapper' },
                  el(
                    'a',
                    {
                      href: ctaLink || '#',
                      className: 'cta-button',
                      style: {
                        display: 'inline-block',
                        padding: '12px 24px',
                        backgroundColor: buttonColor,
                        color: buttonTextColor,
                        textDecoration: 'none',
                        borderRadius: '8px',
                        fontWeight: 'bold',
                        transition: 'all 0.3s ease',
                      },
                    },
                    ctaText || __('Clique Aqui', 'advanced-corretora')
                  )
                )
              ),
              el(
                'div',
                { className: 'cta-image' },
                image
                  ? el('img', {
                      src: image.url,
                      alt: image.alt || '',
                      style: {
                        width: '100%',
                        height: 'auto',
                        borderRadius: '8px',
                      },
                    })
                  : el(
                      'div',
                      {
                        style: {
                          width: '100%',
                          height: '300px',
                          backgroundColor: '#f0f0f0',
                          display: 'flex',
                          alignItems: 'center',
                          justifyContent: 'center',
                          borderRadius: '8px',
                          border: '2px dashed #ccc',
                        },
                      },
                      __('Selecione uma imagem', 'advanced-corretora')
                    )
              )
            )
          )
        )
      );
    },
    save: () => null,
  });

  // console.log('Seção CTA: Bloco registrado com sucesso');
})();
