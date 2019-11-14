Feature: CreditCard3DSPurchaseHappyPath
  As a guest  user
  I want to make a purchase with a Credit Card 3DS
  And to see that transaction was successful

  Background:
    Given I activate "creditcard" payment action "pay" in configuration
    Given I activate one-click checkout in configuration
    And I prepare credit card checkout "3DS"
    And I am on "Checkout" page
    And I fill fields with "Customer Data with password"
    When I check "I agree to the terms and conditions and the privacy policy"
    And I click "Next"
    And I fill fields with "Billing Data"
    Then I select "Wirecard Credit Card"

  @ui_test @env ui_test
  Scenario: purchase
    Given I fill fields with "Valid Credit Card Data"
    When I check "I agree to the terms of service"
    When I check "Save for later use"
    And I click "Order with an obligation to pay"
    And I am redirected to "Verified" page
    And I enter "wirecard" in field "Password"
    And I click "Continue"
    Then I am redirected to "Order Received" page
    And I see "YOUR ORDER IS CONFIRMED"
    And I see "creditcard" "purchase" in transaction table
