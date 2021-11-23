# wapi
Wedos API (WAPI) PHP client

WIP - implementováno pouze to co je momentálně potřeba. PR vítány. 

**IMPLEMENTOVANÉ ENDPOINTY**

| endpoint  |  metoda |
| ------------ | ------------ |
| ping  | ping   |
| domains-list  | domains()   |
| dns-rows-list  | dnsRowsList($domain)  |
| credit-info  | account->credit()  |


**POUŽITÍ**

```php
$wapi = new Wapi\Wapi('login', 'wapi_pass'); // rozdilne od hesla pro ucet

if ( ! $wapi->ping() ) {
    echo "Nelze se připojit k WAPI";
    exit;
}

foreach ($wapi->domains() as $domain) {
	if ($domain->status != 'active') continue;

    foreach ($domain->dnsRecords() as $record) {
        echo $record->rdata;
    }
}
```