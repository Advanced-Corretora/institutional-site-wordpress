<?php
/**
 * Template part for displaying social share buttons
 *
 * @package advanced-corretora
 */

$post_url = urlencode( get_permalink() );
$post_title = urlencode( get_the_title() );
$post_excerpt = urlencode( wp_trim_words( get_the_excerpt(), 20 ) );
?>

<div class="social-share">
	<span class="social-share__label">Compartilhe:</span>
	
	<div class="social-share__buttons">
		<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" 
		   target="_blank" 
		   rel="noopener noreferrer" 
		   class="facebook"
		   title="Compartilhar no Facebook">
			<svg viewBox="0 0 24 24" fill="currentColor">
				<path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 3.667h-3.533v7.98H9.101z"/>
			</svg>
		</a>
		
		<a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" 
		   target="_blank" 
		   rel="noopener noreferrer"
		   class="twitter"
		   title="Compartilhar no X (Twitter)">
			<svg viewBox="0 0 24 24" fill="currentColor">
				<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
			</svg>
		</a>
		
		<a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>" 
		   target="_blank" 
		   rel="noopener noreferrer"
		   class="linkedin"
		   title="Compartilhar no LinkedIn">
			<svg viewBox="0 0 24 24" fill="currentColor">
				<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
			</svg>
		</a>
		
		<a href="https://api.whatsapp.com/send?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>" 
		   target="_blank" 
		   rel="noopener noreferrer"
		   class="whatsapp"
		   title="Compartilhar no WhatsApp">
			<svg viewBox="0 0 24 24" fill="currentColor">
				<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
			</svg>
		</a>
		
		<button type="button" 
				class="copy" 
				onclick="copyToClipboard('<?php echo get_permalink(); ?>', this)"
				title="Copiar link">
			<svg viewBox="0 0 24 24" fill="currentColor">
				<path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
			</svg>
		</button>
	</div>
</div>

<script>
function copyToClipboard(text, buttonElement) {
	if (navigator.clipboard) {
		navigator.clipboard.writeText(text).then(function() {
			// Feedback visual
			buttonElement.classList.add('copy-success');
			
			// Remove a classe após 2 segundos
			setTimeout(() => {
				buttonElement.classList.remove('copy-success');
			}, 2000);
		});
	} else {
		// Fallback para navegadores mais antigos
		const textArea = document.createElement('textarea');
		textArea.value = text;
		document.body.appendChild(textArea);
		textArea.select();
		document.execCommand('copy');
		document.body.removeChild(textArea);
		
		// Feedback visual para fallback
		buttonElement.classList.add('copy-success');
		setTimeout(() => {
			buttonElement.classList.remove('copy-success');
		}, 2000);
	}
}
</script>
