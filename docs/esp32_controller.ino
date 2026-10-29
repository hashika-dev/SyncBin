#include <WiFi.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>

// Your Wi-Fi credentials
const char* ssid     = "SMART_BRO_12F51";
const char* password = "Qweqwe@123";

// Server URL:
// - Local PC testing:  "http://192.168.1.184:8000/api/update_bins"
// - Online cloud:      "https://ecosync.me/api/update_bins"
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

void setup() {
  Serial.begin(115200);

  // Initialize sensor pins for all 4 bins
  pinMode(trigPin1, OUTPUT); pinMode(echoPin1, INPUT);
  pinMode(trigPin2, OUTPUT); pinMode(echoPin2, INPUT);
  pinMode(trigPin3, OUTPUT); pinMode(echoPin3, INPUT);
  pinMode(trigPin4, OUTPUT); pinMode(echoPin4, INPUT);

  // Connect to Wi-Fi
  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi...");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected to WiFi!");
  Serial.print("ESP32 IP Address: ");
  Serial.println(WiFi.localIP());
  Serial.print("MAC Address: ");
  Serial.println(WiFi.macAddress());
  Serial.print("WiFi RSSI: ");
  Serial.print(WiFi.RSSI());
  Serial.println(" dBm");
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
  if (WiFi.status() == WL_CONNECTED) {
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
      secureClient.setInsecure();
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
      Serial.println("Error on sending POST: " + String(httpResponseCode));
    }

    http.end();
  } else {
    Serial.println("WiFi Disconnected. Reconnecting...");
    WiFi.reconnect();
  }

  // Normal cycle interval: 15 seconds
  delay(15000);
}
