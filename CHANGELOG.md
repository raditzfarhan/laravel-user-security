# Release Notes

All notable changes to Laravel User Security - RFAuthenticator will be documented in this file.

## Version 1.0.0 (2020-04-18)

### Added
- Initial commit.
- Add Service Provider.
- Add RFAuthenticator class and Facade.
- Add Mnemonic service class with generate, words and entropy methods.
- Add config file.
- Add user_securities migration file.
- Add UserSecurity model
- Add UserSecurable trait with security relationship, updateSecurityPin, updateEntropy, updateMultipleAuthenticators methods