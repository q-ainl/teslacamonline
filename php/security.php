<?php
// source:      /srv/phlo/libs/security.phlo
// phlo:        1.0β
// version:     1.0
// creator:     q-ai.nl
// description: Generic security library
class security extends obj {
	public $host = host;
	protected function setNonce(){
		return phlo('app')->nonce = token(8);
	}
	protected function strict(){
		return [$this->base, async || [header('Cache-Control: no-store'), header("Content-Security-Policy: default-src 'self'; script-src 'nonce-$this->setNonce'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; form-action 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'")]];
	}
	protected function full(){
		return [$this->base, async || [header('Cache-Control: no-store'), header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$this->setNonce'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; form-action 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'")]];
	}
	protected function basic(){
		return [$this->base, async || header("Content-Security-Policy: default-src 'self'; script-src 'self'".(debug ? " 'unsafe-inline'" : '')."; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; form-action 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'")];
	}
	protected function base(){
		phlo('session')->csrf = token(12);
		header('Referrer-Policy: strict-origin-when-cross-origin');
		header('X-Content-Type-Options: nosniff');
		async || header('Cross-Origin-Opener-Policy: same-origin');
		async || header('Cross-Origin-Resource-Policy: same-origin');
		async || header("Access-Control-Allow-Origin: https://$this->host");
		async || header('X-Frame-Options: DENY');
	}
}
