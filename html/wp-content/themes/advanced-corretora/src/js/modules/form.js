document.addEventListener('DOMContentLoaded', function () {
  const telInput = document.querySelector('input[name="telefone"]');
  const cpfInput = document.querySelector('input[name="cpf"]');

  // Máscara de telefone
  telInput.addEventListener('input', function (e) {
    let v = e.target.value.replace(/\D/g, '');
    v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
    v = v.replace(/(\d{4,5})(\d{4})$/, '$1-$2');
    e.target.value = v;
  });

  // Máscara de CPF (limitado a 11 dígitos)
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
  });

  // Validação simples de CPF (apenas tamanho)
  //   cpfInput.addEventListener('blur', function (e) {
  //     const cpf = e.target.value.replace(/\D/g, '');
  //     if (cpf.length !== 11) {
  //       alert('CPF inválido. Digite no formato 000.000.000-00');
  //       e.target.focus();
  //     }
  //   });
});
