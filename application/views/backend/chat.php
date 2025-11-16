<head>
    <script defer src="https://cdn.jsdelivr.net/npm/@cometchat/chat-embed@latest/dist/main.js"></script>
</head>
<body>
<div id="cometChatMount"></div>
</body>
<script>
  const COMETCHAT_CREDENTIALS = {
    appID:     "16704172b2b4fd0cc",
    appRegion: "us",
    authKey:   "30878c1f63be734960f148af54baf936748d1f9a",
  };

  const COMETCHAT_USER_UID = "UID"; // Replace with your actual user UID

  const COMETCHAT_LAUNCH_OPTIONS = {
    targetElementID: "cometChatMount",   // Element ID to mount the widget
    isDocked:        true,               // true = floating bubble, false = embedded
    width:           "700px",            // Widget width
    height:          "500px",            // Widget height

    // Optional advanced settings:
    // variantID:        "YOUR_VARIANT_ID",    // Variant configuration ID
    // chatType:         "user",               // "user" or "group"
    // defaultChatID:    "uid_or_guid",        // UID or GUID to open chat by default
    // dockedAlignment:  "right",              // For docked mode: "left" or "right"
  };

  window.addEventListener("DOMContentLoaded", () => {
    CometChatApp.init(COMETCHAT_CREDENTIALS)
      .then(() => {
        console.log("[CometChat] Initialized successfully");
        return CometChatApp.login({ uid: COMETCHAT_USER_UID });
      })
      .then(user => {
        console.log("[CometChat] Logged in as:", user);
        return CometChatApp.launch(COMETCHAT_LAUNCH_OPTIONS);
      })
      .then(() => {
        console.log("[CometChat] Chat launched!");
      })
      .catch(error => {
        console.error("[CometChat] Error:", error);
      });
  });
</script>