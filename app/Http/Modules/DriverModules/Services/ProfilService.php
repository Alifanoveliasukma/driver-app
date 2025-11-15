<?php

namespace App\Http\Modules\DriverModules\Services;

use App\Services\DriverApi;

class ProfilService
{

    public static function getDriverProfile($c_bpartner_id, DriverApi $driverApi)
    {
        $driver = $driverApi->getDriver($c_bpartner_id);

        $fields = data_get($driver, 'soap:Body.ns1:queryDataResponse.WindowTabData.DataSet.DataRow.field', []);
        if (isset($fields['@attributes'])) {
            $fields = [$fields];
        }

        $mappedDriver = [];
        foreach ($fields as $f) {
            $attr = $f['@attributes'] ?? [];
            if (isset($attr['column'], $attr['lval'])) {
                $mappedDriver[$attr['column']] = $attr['lval'];
            }
        }

        return [
            'driverId' => $mappedDriver['XM_Driver_ID'] ?? null,
            'name' => $mappedDriver['Name'] ?? null,
            'accountNo' => $mappedDriver['AccountNo'] ?? null,
            'account' => $mappedDriver['Account'] ?? null,
            'note' => $mappedDriver['Note'] ?? null,
            'value' => $mappedDriver['Value'] ?? null,
            'fleetId' => $mappedDriver['XM_Fleet_ID'] ?? null,
            'kraniId' => $mappedDriver['Krani_ID'] ?? null,
        ];
    }
}
