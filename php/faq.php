<?php
// source: /srv/teslacam.online/faq.phlo
// phlo:   1.0β
class faq extends obj {
	public static function BothGETFaq(){
		return view(phlo('faq'), 'FAQ', scroll: 0);
	}
	protected function view():string {
		$phloView = [];
		$phloView[] = "<div id=\"welcome\" class=\"welcome\">";
		$phloView[] = "\t<div class=\"wc\">";
		$phloView[] = "\t\t<div class=\"topbar\">";
		$phloView[] = "\t\t\t<a class=\"async\" href=\"/\">Home</a>";
		$phloView[] = "\t\t\t<a class=\"btn\" href=\"/viewer\" target=\"_blank\">Open Viewer</a>";
		$phloView[] = "\t\t</div>";
		$phloView[] = "\t\t<h1>TeslaCam Viewer</h1>";
		$phloView[] = "\t\t<h2 id=\"faq\">FAQ</h2>";
		$phloView[] = "\t\t<h3>Does the Viewer upload my videos anywhere?</h3>";
		$phloView[] = "\t\t<p>No. All processing happens locally in your browser. Nothing is sent to any server.</p>";
		$phloView[] = "\t\t<h3>Can I use the Viewer without internet?</h3>";
		$phloView[] = "\t\t<p>Yes. <a href=\"/download\">Save the page as HTML</a> and open that file directly in your browser. You will still need to select your TeslaCam folder from your computer or drive.</p>";
		$phloView[] = "\t\t<h3>Which browsers are supported?</h3>";
		$phloView[] = "\t\t<p>Recent versions of Chromium-based browsers (Chrome, Edge, Brave, Opera) and other modern browsers are recommended. Safari generally works but may handle local file permissions differently. Always keep your browser up to date.</p>";
		$phloView[] = "\t\t<h3>The app says \"No clips match your filters.\" What should I check?</h3>";
		$phloView[] = "\t\t<ul>";
		$phloView[] = "\t\t\t<li>Clear filters using the \"Show full list\" row or the Clear button in Location.</li>";
		$phloView[] = "\t\t\t<li>Verify that the standard folders <strong>RecentClips</strong>, <strong>SavedClips</strong>, and <strong>SentryClips</strong> exist and contain files.</li>";
		$phloView[] = "\t\t\t<li>Ensure your clips use Tesla's default filenames (e.g., <code>YYYY-MM-DD_HH-MM-SS-front.mp4</code>).</li>";
		$phloView[] = "\t\t</ul>";
		$phloView[] = "\t\t<h3>Why do some events show only one or two cameras?</h3>";
		$phloView[] = "\t\t<p>Some events may not have recordings from all cameras due to how Tesla captured the footage at the time. The Viewer displays whatever is present.</p>";
		$phloView[] = "\t\t<h3>The map doesn't show markers. Why?</h3>";
		$phloView[] = "\t\t<p>Markers rely on event metadata (<code>event.json</code>) with approximate latitude/longitude. If those values are missing or invalid, the map won't show markers.</p>";
		$phloView[] = "\t\t<h3>Playback stutters between files. Any tips?</h3>";
		$phloView[] = "\t\t<ul>";
		$phloView[] = "\t\t\t<li>Play from local storage (internal SSD) instead of a slow USB stick or network share.</li>";
		$phloView[] = "\t\t\t<li>Close other heavy tabs to free resources.</li>";
		$phloView[] = "\t\t\t<li>If a clip is corrupted, the browser may struggle to decode it; try another browser.</li>";
		$phloView[] = "\t\t</ul>";
		$phloView[] = "\t\t<h3>Can I export or edit videos?</h3>";
		$phloView[] = "\t\t<p>The Viewer focuses on browsing and synchronized playback. It does not edit or export; please use dedicated video tools for that purpose.</p>";
		$phloView[] = "\t\t<h3>Can I share this app with friends or colleagues?</h3>";
		$phloView[] = "\t\t<p>Yes. It's a single HTML file, easy to share. Remind them that it's donationware if they find it useful.</p>";
		$phloView[] = "\t\t<hr>";
		$phloView[] = "\t\t<h2 id=\"donate\">Donationware</h2>";
		$phloView[] = "\t\t<p>The app is free to use. If it saves you time or proves helpful, please consider supporting the project. Thank you!</p>";
		$phloView[] = "\t\t".indentView(phlo('app')->support , 2)."";
		$phloView[] = "\t\t<hr>";
		$phloView[] = "\t\t<h3>How do you fund this project?</h3>";
		$phloView[] = "\t\t<p>";
		$phloView[] = "\t\t\tTeslaCam Viewer is developed as part of the <strong>Q-AI</strong> ecosystem.";
		$phloView[] = "\t\t\tQ-AI focuses on building small, focused web tools that run locally and respect";
		$phloView[] = "\t\t\tuser privacy.";
		$phloView[] = "\t\t</p>";
		$phloView[] = "\t\t<p>";
		$phloView[] = "\t\t\tThis project is primarily supported through voluntary donations.";
		$phloView[] = "\t\t\tThere are no ads, no tracking, and no monetization of user data.";
		$phloView[] = "\t\t</p>";
		$phloView[] = "\t\t<a href=\"https://q-ai.nl/\" target=\"_blank\" rel=\"noopener\"><img style=\"float:right\" src=\"/logo.png\" alt=\"Q-AI logo\"></a>";
		$phloView[] = "\t\t<p>";
		$phloView[] = "\t\t\tQ-AI also develops other tools, such as <a href=\"https://ecms.nu/\" target=\"_blank\" rel=\"noopener\"><strong>eCMS</strong></a> and";
		$phloView[] = "\t\t\t<a href=\"https://filesystem.online/\" target=\"_blank\" rel=\"noopener\"><strong>Filesystem.online</strong></a>. Together, these projects allow continued";
		$phloView[] = "\t\t\tdevelopment without relying on subscriptions or intrusive revenue models.";
		$phloView[] = "\t\t</p>";
		$phloView[] = "\t\t<p>Learn more about Q-AI at <a href=\"https://q-ai.nl/\" target=\"_blank\" rel=\"noopener\">Q-AI.nl</a>.</p>";
		$phloView[] = "\t\t<hr>";
		$phloView[] = "\t\t<h2 id=\"troubleshooting\">Troubleshooting Checklist</h2>";
		$phloView[] = "\t\t<ul>";
		$phloView[] = "\t\t\t<li>Reload the page and reselect the TeslaCam folder.</li>";
		$phloView[] = "\t\t\t<li>Confirm the folder contains <strong>RecentClips</strong>, <strong>SavedClips</strong>, and <strong>SentryClips</strong>.</li>";
		$phloView[] = "\t\t\t<li>Try a different browser if playback or file permissions misbehave.</li>";
		$phloView[] = "\t\t\t<li>Move the clips to a faster disk if you notice I/O bottlenecks.</li>";
		$phloView[] = "\t\t</ul>";
		$phloView[] = "\t\t<hr>";
		$phloView[] = "\t\t<h2 id=\"credits\">Credits</h2>";
		$phloView[] = "\t\t<p>Tesla is a trademark of Tesla, Inc. This project is an independent Viewer designed for personal use with locally stored footage.</p>";
		$phloView[] = "\t</div>";
		$phloView[] = "</div>";
		return implode(lf, $phloView);
	}
}
