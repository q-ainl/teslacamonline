<?php
// source: /srv/teslacam.online/viewer.phlo
// phlo:   1.0β
class viewer extends obj {
	public static function GETViewer(){
		return view(phlo('viewer'), ns: 'viewer');
	}
	public static function GETDownload(){
		return output (
			DOM (
				phlo('viewer')->view.lf.tag('script', lf.file_get_contents(www.'viewer.js')),
				phlo('viewer')->head.lf.tag('style', lf.file_get_contents(www.'viewer.css').lf).lf,
			),
			'TeslaCamOnline.html',
			true,
		);
	}
	protected function head():string {
		$phloView = [];
		$phloView[] = "<title>TeslaCam Viewer</title>";
		$phloView[] = "<meta name=\"viewport\" content=\"width=device-width\">";
		return implode(lf, $phloView);
	}
	protected function view():string {
		$phloView = [];
		$phloView[] = "<div id=\"app\">";
		$phloView[] = "\t<aside id=\"sidebar\" class=\"sidebar\">";
		$phloView[] = "\t\t<header>";
		$phloView[] = "\t\t<button id=\"openBtn\" class=\"action\">Open folder</button>";
		$phloView[] = "\t\t<input id=\"dirInput\" type=\"file\" webkitdirectory directory multiple hidden>";
		$phloView[] = "\t\t<button id=\"locBtn\" class=\"action toggle\">Map</button>";
		$phloView[] = "\t\t<button id=\"settingsBtn\" class=\"action toggle\">⚙</button>";
		$phloView[] = "\t\t</header>";
		$phloView[] = "\t\t<div id=\"settings\" class=\"settings\">";
		$phloView[] = "\t\t\t<div class=\"row\">";
		$phloView[] = "\t\t\t\t<label class=\"small\">Location</label>";
		$phloView[] = "\t\t\t\t<div class=\"rowLoc\">";
		$phloView[] = "\t\t\t\t\t<select id=\"citySelect\" class=\"select\"><option value=\"\">All locations</option></select>";
		$phloView[] = "\t\t\t\t\t<button id=\"clearArea\" class=\"action\" style=\"padding:8px 12px\">Clear</button>";
		$phloView[] = "\t\t\t\t</div>";
		$phloView[] = "\t\t\t</div>";
		$phloView[] = "\t\t\t<div class=\"row row3\">";
		$phloView[] = "\t\t\t\t<div class=\"fcol\"><label class=\"small\">Speed</label><input id=\"speed\" class=\"range\" type=\"range\" min=\"0.1\" max=\"4\" step=\"0.3\" value=\"1\"></div>";
		$phloView[] = "\t\t\t\t<div class=\"fcol\"><label class=\"small\">Brightness</label><input id=\"bri\" class=\"range\" type=\"range\" min=\"0.2\" max=\"2\" step=\"0.05\" value=\"1\"></div>";
		$phloView[] = "\t\t\t\t<div class=\"fcol\"><label class=\"small\">Contrast</label><input id=\"con\" class=\"range\" type=\"range\" min=\"0.5\" max=\"2\" step=\"0.05\" value=\"1\"></div>";
		$phloView[] = "\t\t\t</div>";
		$phloView[] = "\t\t</div>";
		$phloView[] = "\t\t<div id=\"filterBar\" class=\"filterBar\">";
		$phloView[] = "\t\t\t<div class=\"fb-left\">";
		$phloView[] = "\t\t\t\t<label class=\"chk\"><input class=\"srcChk\" type=\"checkbox\" value=\"Sentry\" checked>Sentry</label>";
		$phloView[] = "\t\t\t\t<label class=\"chk\"><input class=\"srcChk\" type=\"checkbox\" value=\"Recent\" checked>Recent</label>";
		$phloView[] = "\t\t\t\t<label class=\"chk\"><input class=\"srcChk\" type=\"checkbox\" value=\"Saved\" checked>Saved</label>";
		$phloView[] = "\t\t\t</div>";
		$phloView[] = "\t\t\t<button id=\"sortToggle\" class=\"sortToggle\" title=\"Toggle sort order\">▼</button>";
		$phloView[] = "\t\t</div>";
		$phloView[] = "\t\t<div id=\"list\" class=\"list\"><p class=\"no-data\">Open a TeslaCam folder to begin.</p></div>";
		$phloView[] = "\t\t<div id=\"controls\" class=\"controls\">";
		$phloView[] = "\t\t\t<div class=\"headline\"><span id=\"dateBig\" class=\"datebig\">-</span><span id=\"timeBig\" class=\"timebig\">-:-:-</span><span id=\"locBig\" class=\"locbig\"></span></div>";
		$phloView[] = "\t\t\t<div class=\"time\"><span id=\"tStart\" class=\"tlabel\">-</span><div class=\"scrubWrap\"><input id=\"scrubber\" class=\"scrubber\" type=\"range\" min=\"0\" max=\"60\" step=\"0.01\" value=\"0\"><div id=\"scrubMarks\" class=\"scrubMarks\"></div></div><span id=\"tEnd\" class=\"tlabel\">-</span></div>";
		$phloView[] = "\t\t\t<div class=\"ctrlrow\"><button id=\"playBtn\" class=\"btn\">▶</button><span id=\"mSource\" class=\"muted\"></span><span id=\"mReason\" class=\"muted\" style=\"display:none\"></span><span id=\"speedBadge\" class=\"badge\" title=\"Playback speed\">1×</span></div>";
		$phloView[] = "\t\t</div>";
		$phloView[] = "\t</aside>";
		$phloView[] = "\t<main class=\"main\">";
		$phloView[] = "\t\t<button id=\"menuBtn\">☰</button>";
		$phloView[] = "\t\t<div id=\"grid\" class=\"grid-quad\"></div>";
		$phloView[] = "\t\t<div id=\"thumbRow\"></div>";
		$phloView[] = "\t\t<div id=\"mapWrap\"><div id=\"map\"></div><button id=\"mapToggle\">⤢</button></div>";
		$phloView[] = "\t</main>";
		$phloView[] = "</div>";
		return implode(lf, $phloView);
	}
}
