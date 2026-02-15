<?php
// source: /srv/teslacam.online/app.phlo
// phlo:   1.0β
class app extends obj {
	protected function controller(){
		app::route();
		view($this->notFound, 404);
	}
	public static function route(){
		route('PUT', 'heartbeat', false, data: 'n,v,l,u,w,h,a,p,r', cb: 'visitors::PUTHeartbeatNVLUWHAPR');
		route('GET', cb: 'app::BothGETHome');
		route('GET', 'faq', cb: 'faq::BothGETFaq');
		route('GET', 'info', cb: 'info::BothGETInfo');
		route('GET', 'viewer', false, cb: 'viewer::GETViewer');
		route('GET', 'download', false, cb: 'viewer::GETDownload');
	}
	public $lang = 'en';
	protected function _title(){
		return 'TeslaCam Online'.(req ? void : " | $this->slogan");
	}
	public $slogan = 'Extended Tesla Dashcam viewer';
	public $description = 'Browse and play TeslaCam Sentry, Recent and Saved clips locally in your browser with map preview and multi-camera sync.';
	protected function _token(){
		return phlo('cookies')->token ??= token(20);
	}
	public $version = '1.0.3';
	public static function BothGETHome(){
		return view(phlo('app'), scroll: 0);
	}
	protected function head():string {
		$phloView = [];
		$phloView[] = "<meta property=\"og:title\" content=\"".title()."\">";
		$phloView[] = "<meta property=\"og:description\" content=\"Open your TeslaCam folder and view Sentry/Recent/Saved clips with a map and multi-camera layout. Everything runs locally in your browser.\">";
		$phloView[] = "<meta property=\"og:url\" content=\"https://".host.slash.esc(req)."\">";
		$phloView[] = "<meta property=\"og:image\" content=\"https://".host."/viewer.webp\">";
		$phloView[] = "<link rel=\"canonical\" href=\"https://".host.slash.esc(req)."\">";
		return implode(lf, $phloView);
	}
	protected function view():string {
		$phloView = [];
		$phloView[] = "<div id=\"welcome\" class=\"welcome\">";
		$phloView[] = "\t<div class=\"wc\">";
		$phloView[] = "\t\t<h1>Welcome to TeslaCam Viewer</h1>";
		$phloView[] = "\t\t<h2>Local-only dashcam viewer</h2>";
		$phloView[] = "\t\t<p>Easily explore and relive the moments captured by your Tesla’s built-in cameras. TeslaCam.online viewer lets you browse, filter and play back your TeslaCam Sentry, Recent and Saved clips. All <strong>locally</strong> in your browser with map preview and multi-camera sync.</p>";
		$phloView[] = "\t\t<ul class=\"highlights\">";
		$phloView[] = "\t\t\t<li><strong>Instant browsing</strong> of TeslaCam events</li>";
		$phloView[] = "\t\t\t<li><strong>Multi-camera sync</strong> in a single player</li>";
		$phloView[] = "\t\t\t<li><strong>100% local & private</strong></li>";
		$phloView[] = "\t\t</ul>";
		$phloView[] = "\t\t<p><strong>No uploads, no installs:</strong> your videos stay on your device. The viewer runs fully standalone inside this single page. Simply click <em>“Open folder”</em> and select your TeslaCam root directory to get started.</p>";
		$phloView[] = "\t\t<p class=\"img\"><iframe src=\"https://www.youtube.com/embed/m8lQ1SCjJow\" allow=\"fullscreen\"></iframe></p>";
		$phloView[] = "\t\t<p class=\"cta\"><a href=\"/viewer\" target=\"_blank\" class=\"btn\">Open Viewer</a></p>";
		$phloView[] = "\t\t<h2>How it works</h2>";
		$phloView[] = "\t\t<ul class=\"how-it-works\">";
		$phloView[] = "\t\t\t<li>Open TeslaCam Viewer and click <strong>“Open folder”</strong></li>";
		$phloView[] = "\t\t\t<li>Select your <strong>TeslaCam root directory</strong> from your drive</li>";
		$phloView[] = "\t\t\t<li>Browse, filter and play your clips with map preview and multi-camera sync</li>";
		$phloView[] = "\t\t</ul>";
		$phloView[] = "\t\t<p>Want to know more? See the <a class=\"async\" href=\"/faq\">FAQ</a> for quick answers or visit the <a class=\"async\" href=\"/info\">info page</a> for a detailed overview.</p>";
		$phloView[] = "\t\t".indentView(phlo('app')->qai , 2)."";
		$phloView[] = "\t\t<h2>Support the development</h2>";
		$phloView[] = "\t\t<p>This project is donationware: free to use, with optional support if you’d like to give back. Your support helps keep it free, offline-capable, and actively maintained.</p>";
		$phloView[] = "\t\t".indentView(phlo('app')->support , 2)."";
		$phloView[] = "\t</div>";
		$phloView[] = "</div>";
		return implode(lf, $phloView);
	}
	protected function support():string {
		$phloView = [];
		$phloView[] = "<div class=\"support-grid\">";
		$phloView[] = "\t<a class=\"support-card paypal\" href=\"https://www.paypal.me/jordiboerman\" target=\"_blank\" rel=\"noopener\"><span class=\"logo\"><span class=\"icon paypal\" aria-hidden=\"true\"></span></span><span class=\"label\">PayPal</span></a>";
		$phloView[] = "\t<a class=\"support-card kofi\" href=\"https://ko-fi.com/jordiboerman\" target=\"_blank\" rel=\"noopener\"><span class=\"logo\"><span class=\"icon kofi\" aria-hidden=\"true\"></span></span><span class=\"label\">Ko-fi</span></a>";
		$phloView[] = "\t<a class=\"support-card bmc\" href=\"https://www.buymeacoffee.com/jordiboerman\" target=\"_blank\" rel=\"noopener\"><span class=\"logo\"><span class=\"icon bmc\" aria-hidden=\"true\"></span></span><span class=\"label\">Buy Me a Coffee</span></a>";
		$phloView[] = "</div>";
		return implode(lf, $phloView);
	}
	protected function qai():string {
		$phloView = [];
		$phloView[] = "<div id=\"qai\" class=\"qai\">";
		$phloView[] = "\t<div class=\"qai-content\">";
		$phloView[] = "\t\t<h2>Discover Q-AI</h2>";
		$phloView[] = "\t\t<p><strong>TeslaCam Viewer</strong> is part of Q-AI.</p>";
		$phloView[] = "\t\t<p>Browse the growing Q-AI ecosystem of high-tech, convenience-driven web tools and see what else is being built.</p>";
		$phloView[] = "\t\t<a class=\"qai-btn\" href=\"https://q-ai.nl/\" target=\"_blank\" rel=\"noopener\">Explore Q-AI</a>";
		$phloView[] = "\t</div>";
		$phloView[] = "\t<div class=\"qai-logo\">";
		$phloView[] = "\t\t<a href=\"https://q-ai.nl/\" target=\"_blank\" rel=\"noopener\"><img src=\"/logo.png\" alt=\"Q-AI logo\"></a>";
		$phloView[] = "\t</div>";
		$phloView[] = "</div>";
		return implode(lf, $phloView);
	}
	protected function notFound():string {
		$phloView = [];
		$phloView[] = "<div id=\"welcome\" class=\"welcome\">";
		$phloView[] = "\t<div class=\"wc\">";
		$phloView[] = "\t\t<h1>Page not found</h1>";
		$phloView[] = "\t</div>";
		$phloView[] = "</div>";
		return implode(lf, $phloView);
	}
}
