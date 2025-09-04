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

registerBlockType('advanced-corretora/sessao-numeros', {
  edit: ({ attributes, setAttributes }) => {
    const {
      title,
      subtitle,
      numbers,
      textAlignment,
      backgroundColor,
      textColor,
      numberColor,
      backgroundImage,
      backgroundImageId,
      overlayOpacity,
      overlayColor,
    } = attributes;

    const blockProps = useBlockProps({
      className: `sessao-numeros-block align-${textAlignment}`,
      style: {
        backgroundColor: backgroundImage ? 'transparent' : backgroundColor,
        backgroundImage: backgroundImage ? `url(${backgroundImage.url})` : 'none',
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        backgroundRepeat: 'no-repeat',
        color: textColor,
        // padding: '60px 20px',
        textAlign: textAlignment,
        position: 'relative',
      },
    });

    const addNumber = () => {
      const newNumbers = [...numbers, { number: '0', label: 'Nova Estatística' }];
      setAttributes({ numbers: newNumbers });
    };

    const updateNumber = (index, field, value) => {
      const newNumbers = [...numbers];
      newNumbers[index][field] = value;
      setAttributes({ numbers: newNumbers });
    };

    const removeNumber = index => {
      const newNumbers = numbers.filter((_, i) => i !== index);
      setAttributes({ numbers: newNumbers });
    };

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
              value: numberColor,
              onChange: color => setAttributes({ numberColor: color }),
              label: __('Cor dos Números', 'advanced-corretora'),
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
          {
            title: __('Imagem de Fundo', 'advanced-corretora'),
            initialOpen: false,
          },
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
          {
            title: __('Gerenciar Números', 'advanced-corretora'),
            initialOpen: false,
          },
          ...numbers.map((item, index) =>
            el(
              'div',
              {
                key: index,
                style: {
                  marginBottom: '15px',
                  padding: '10px',
                  border: '1px solid #ddd',
                  borderRadius: '4px',
                },
              },
              el(TextControl, {
                label: __('Número/Estatística', 'advanced-corretora'),
                value: item.number,
                onChange: value => updateNumber(index, 'number', value),
              }),
              el(TextControl, {
                label: __('Descrição', 'advanced-corretora'),
                value: item.label,
                onChange: value => updateNumber(index, 'label', value),
              }),
              el(
                Button,
                {
                  isDestructive: true,
                  variant: 'link',
                  onClick: () => removeNumber(index),
                },
                __('Remover', 'advanced-corretora')
              )
            )
          ),
          el(
            Button,
            {
              isPrimary: true,
              onClick: addNumber,
            },
            __('Adicionar Número', 'advanced-corretora')
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
          { className: 'container', style: { maxWidth: '1200px', margin: '0 auto' } },
          el(RichText, {
            tagName: 'h2',
            placeholder: __('Digite o título da seção...', 'advanced-corretora'),
            value: title,
            onChange: value => setAttributes({ title: value }),
            style: {
              fontSize: '2.5rem',
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
              marginBottom: '3rem',
              opacity: 0.8,
              color: textColor,
            },
          }),
          el(
            'div',
            {
              className: 'numbers-grid',
              style: {
                display: 'grid',
                gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
                gap: '2rem',
                marginTop: '2rem',
              },
            },
            ...numbers.map((item, index) =>
              el(
                'div',
                {
                  key: index,
                  className: 'number-item',
                  style: {
                    textAlign: 'center',
                    padding: '1.5rem',
                  },
                },
                el(
                  'div',
                  {
                    className: 'number',
                    style: {
                      fontSize: '2rem',
                      fontWeight: 'bold',
                      color: numberColor,
                      marginBottom: '0.5rem',
                      lineHeight: '1',
                    },
                  },
                  item.number
                ),
                el(
                  'div',
                  {
                    className: 'label',
                    style: {
                      fontSize: '1rem',
                      color: textColor,
                      fontWeight: '500',
                    },
                  },
                  item.label
                )
              )
            )
          )
        )
      )
    );
  },
});
