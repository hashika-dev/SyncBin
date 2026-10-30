#include <WiFi.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>

// ==============================================================================
// 📶 Wi-Fi / Android Mobile Hotspot Configuration
// ==============================================================================
// CRITICAL ANDROID HOTSPOT SETTINGS:
// 1. AP Band: MUST be set to "2.4 GHz" (ESP32 hardware does NOT support 5 GHz).
//    - Samsung: Settings > Mobile Hotspot > Configure > Band > Select "2.4 GHz"
//    - Pixel / Xiaomi / OnePlus: Turn ON "Extend compatibility" or set AP Band to 2.4 GHz.
//    - Turn OFF "Wi-Fi 6 hotspot" if present.
// 2. Hotspot Timeout: Turn OFF "Turn off hotspot automatically" / "Timeout settings"
//    so Android doesn't shut down the hotspot when idle.
// 3. Mobile Data: Ensure cellular mobile data is enabled so ESP32 can reach the cloud.
// 4. Security: WPA2-Personal (or WPA2/WPA3-Personal). Password must be at least 8 chars.
// ==============================================================================
const char* ssid     = "YOUR_ANDROID_HOTSPOT_NAME";      // <-- Put your Android Hotspot SSID here
const char* password = "YOUR_ANDROID_HOTSPOT_PASSWORD";  // <-- Put your Android Hotspot Password here

// Server URL:
// - Online cloud production: "https://ecosync.me/api/update_bins"
// - Local PC testing:        "http://<YOUR_PC_HOTSPOT_IP>:8000/api/update_bins"
const char* serverName = "https://ecosync.me/api/update_bins";

// Pin definitions for your 4 bins
// NOTE: Connect sensor VCC pins to ESP32 'VIN' (5V) pin, NOT '3V3'!
// Standard HC-SR04 sensors require 5V. Powering 4 sensors from 3V3 overloads
// the onboard 3.3V regulator and causes "Brownout detector was triggered" resets.
const int trigPin1 = 12; // Bin 1: Hazardous
const int echoPin1 = 13;
const int trigPin2 = 27; // Bin 2: Recyclable
const int echoPin2 = 26;
const int trigPin3 = 25; // Bin 3: Non-Bio
const int echoPin3 = 33;
const int trigPin4 = 32; // Bin 4: Biodegradable
const int echoPin4 = 35;

void connectToWiFi() {
  WiFi.disconnect(true);
  delay(100);

  // Explicitly set Wi-Fi Station mode
  WiFi.mode(WIFI_STA);
  WiFi.setAutoReconnect(true);

  // Disable Wi-Fi modem sleep: crucial for Android Mobile Hotspots
  // Android hotspots frequently disconnect ESP32 clients when power-saving sleep is active
  WiFi.setSleep(false);

  Serial.println("\n-------------------------------------------");
  Serial.print("Connecting to Android Hotspot: ");
  Serial.println(ssid);
  Serial.println("-------------------------------------------");

  WiFi.begin(ssid, password);

  int attempts = 0;
  const int maxAttempts = 40; // 20 seconds timeout (40 * 500ms)
  while (WiFi.status() != WL_CONNECTED && attempts < maxAttempts) {
    delay(500);
    Serial.print(".");
    attempts++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n[SUCCESS] Connected to Mobile Hotspot!");
    Serial.print("ESP32 IP Address : "); Serial.println(WiFi.localIP());
    Serial.print("Gateway (Phone)  : "); Serial.println(WiFi.gatewayIP());
    Serial.print("MAC Address      : "); Serial.println(WiFi.macAddress());
    Serial.print("WiFi RSSI        : "); Serial.print(WiFi.RSSI()); Serial.println(" dBm");
  } else {
    Serial.println("\n[WARNING] Could not connect to Hotspot within 20 seconds.");
    Serial.println("Troubleshooting Checklist:");
    Serial.println(" 1. Check your phone's Hotspot settings:");
    Serial.println("    - Set 'Band' to 2.4 GHz (or enable 'Extend Compatibility').");
    Serial.println("    - Disable 'Wi-Fi 6' on mobile hotspot.");
    Serial.println(" 2. Confirm SSID and password match exactly (case-sensitive).");
    Serial.println(" 3. Ensure Hotspot is turned ON on your Android phone.");
    Serial.println(" Will continue attempting background reconnects...");
  }
}

void setup() {
  Serial.begin(115200);
  delay(1000);

  // Initialize sensor pins for all 4 bins
  pinMode(trigPin1, OUTPUT); pinMode(echoPin1, INPUT);
  pinMode(trigPin2, OUTPUT); pinMode(echoPin2, INPUT);
  pinMode(trigPin3, OUTPUT); pinMode(echoPin3, INPUT);
  pinMode(trigPin4, OUTPUT); pinMode(echoPin4, INPUT);

  // Connect to Android Hotspot
  connectToWiFi();
}

long getDistance(int trigPin, int echoPin) {
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  long duration = pulseIn(echoPin, HIGH, 30000); // 30ms timeout to prevent hanging if sensor is unplugged
  if (duration == 0) return 0; // Return 0 if not connected/no echo
  return duration * 0.034 / 2; // Distance in cm
}

void loop() {
  // Ensure connection is maintained
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("\n[WIFI] Hotspot disconnected. Reconnecting...");
    connectToWiFi();
    if (WiFi.status() != WL_CONNECTED) {
      Serial.println("[WIFI] Reconnect attempt failed. Waiting 5 seconds before retry...");
      delay(5000);
      return;
    }
  }

  // Read all ultrasonic sensors with staggering to avoid acoustic interference
  long hazardousDist   = getDistance(trigPin1, echoPin1);
  delay(40); // 40ms stagger to prevent current surge and acoustic cross-talk
  long recyclableDist  = getDistance(trigPin2, echoPin2);
  delay(40);
  long nonBioDist      = getDistance(trigPin3, echoPin3);
  delay(40);
  long bioDist         = getDistance(trigPin4, echoPin4);

  // Read real-time Wi-Fi signal strength in dBm
  int wifiRssi = WiFi.RSSI();

  HTTPClient http;
  bool isHttps = String(serverName).startsWith("https://");

  WiFiClient plainClient;
  WiFiClientSecure secureClient;

  // Use secure client for HTTPS, standard client for HTTP
  if (isHttps) {
    secureClient.setInsecure(); // Skip certificate verification for embedded edge node
    http.begin(secureClient, serverName);
  } else {
    http.begin(plainClient, serverName);
  }

  http.setTimeout(15000); // 15-second network timeout
  http.setFollowRedirects(HTTPC_STRICT_FOLLOW_REDIRECTS);
  http.setUserAgent("SyncBin-ESP32-Node/1.0");
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  // Payload containing all 4 bin values + hardware telemetry
  String httpRequestData = "hazardous=" + String(hazardousDist) +
                           "&recyclable=" + String(recyclableDist) +
                           "&non_bio=" + String(nonBioDist) +
                           "&biodegradable=" + String(bioDist) +
                           "&node_id=NODE-01" +
                           "&mac_address=" + String(WiFi.macAddress()) +
                           "&firmware=EcoSync-v2.1.4" +
                           "&protocol=Wi-Fi / " + (isHttps ? "HTTPS" : "HTTP") +
                           "&wifi_rssi=" + String(wifiRssi) +
                           "&ip_address=" + WiFi.localIP().toString();

  Serial.println("\n[HTTP] Sending telemetry to: " + String(serverName));
  int httpResponseCode = http.POST(httpRequestData);

  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("HTTP Response code: " + String(httpResponseCode));
    Serial.println(response);

    // Backoff if edge rate-limited
    if (httpResponseCode == 429) {
      Serial.println("[RATE LIMIT] Cloud edge cooldown triggered. Waiting 60s before retry...");
      http.end();
      delay(60000); // 1-minute backoff
      return;
    }
  } else {
    Serial.print("Error on sending POST: ");
    Serial.println(httpResponseCode);
    Serial.println("Hint: Make sure mobile data is enabled on the Android hotspot phone.");
  }

  http.end();

  // Normal cycle interval: 15 seconds
  delay(15000);
}
