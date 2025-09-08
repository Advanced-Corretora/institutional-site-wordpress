document.addEventListener('DOMContentLoaded', function () {
  const telInput = document.querySelector('input[name="telefone"]');
  const cpfInput = document.querySelector('input[name="cpf"]');
  const submitButton = document.querySelector('.form-submit input[type="submit"]');

  // Campos obrigatórios
  const requiredFields = [
    'input[name="nome"]',
    'input[name="sobrenome"]',
    'input[name="email"]',
    'input[name="telefone"]',
    'input[name="cpf"]',
    'select[name="servicos"]',
    'textarea[name="necessidade"]',
  ];

  // Função para validar todos os campos obrigatórios
  function validateForm() {
    let allFieldsFilled = true;

    requiredFields.forEach(selector => {
      const field = document.querySelector(selector);
      if (field) {
        const value = field.value.trim();
        if (!value) {
          allFieldsFilled = false;
        }

        // Validação específica para CPF (deve ter 11 dígitos)
        if (field.name === 'cpf') {
          const cpfDigits = value.replace(/\D/g, '');
          if (cpfDigits.length !== 11) {
            allFieldsFilled = false;
          }
        }

        // Validação específica para telefone (deve ter pelo menos 10 dígitos)
        if (field.name === 'telefone') {
          const phoneDigits = value.replace(/\D/g, '');
          if (phoneDigits.length < 10) {
            allFieldsFilled = false;
          }
        }
      }
    });

    // Habilita/desabilita o botão de submit
    if (submitButton) {
      submitButton.disabled = !allFieldsFilled;
    }
  }

  // Inicializa o botão como desabilitado
  if (submitButton) {
    submitButton.disabled = true;
  }

  // Função para atualizar visual dos radio buttons
  function updateRadioButtons() {
    const radioButtons = document.querySelectorAll('.urgencia-buttons input[type="radio"]');
    radioButtons.forEach(radio => {
      const label = radio.closest('label');
      if (label) {
        if (radio.checked) {
          label.classList.add('selected');
        } else {
          label.classList.remove('selected');
        }
      }
    });
  }

  // Adiciona listeners para todos os campos obrigatórios
  requiredFields.forEach(selector => {
    const field = document.querySelector(selector);
    if (field) {
      field.addEventListener('input', validateForm);
      field.addEventListener('change', validateForm);
    }
  });

  // Adiciona listeners específicos para radio buttons
  const radioButtons = document.querySelectorAll('.urgencia-buttons input[type="radio"]');
  radioButtons.forEach(radio => {
    radio.addEventListener('change', function () {
      updateRadioButtons();
      validateForm();
    });
  });

  // Inicializa o estado visual dos radio buttons
  updateRadioButtons();

  // Máscara de telefone
  if (telInput) {
    telInput.addEventListener('input', function (e) {
      let v = e.target.value.replace(/\D/g, '');
      v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
      v = v.replace(/(\d{4,5})(\d{4})$/, '$1-$2');
      e.target.value = v;
      validateForm(); // Revalida após aplicar máscara
    });
  }

  // Máscara de CPF (limitado a 11 dígitos)
  if (cpfInput) {
    cpfInput.addEventListener('input', function (e) {
      let v = e.target.value.replace(/\D/g, '');

      // Limita a 11 dígitos
      if (v.length > 11) {
        v = v.slice(0, 11);
      }

      v = v.replace(/(\d{3})(\d)/, '$1.$2');
      v = v.replace(/(\d{3})(\d)/, '$1.$2');
      v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
      e.target.value = v;
      validateForm(); // Revalida após aplicar máscara
    });
  }
});
