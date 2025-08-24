<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
xmlns:adin="http://3e.pl/ADInterface"> 
   <soapenv:Header/> 
   <soapenv:Body> 
      <adin:createData> 
         <adin:ModelCRUDRequest> 
            <adin:ModelCRUD> 
               <adin:serviceType>API-CPOINT</adin:serviceType> 
               <adin:TableName>XX_TransTracking</adin:TableName> 
               <adin:RecordID>0</adin:RecordID> 
               <adin:Action>Create</adin:Action> 
               <!--Optional:--> 
               <adin:DataRow> 
                  <!--Zero or more repetitions:--> 
                  <adin:field column="OrderID"> 
                     <adin:val>1455588</adin:val> 
                  </adin:field> 
                  <adin:field column="DateDoc"> 
                     <adin:val>2028-08-23 10:00:00</adin:val> 
                  </adin:field> 
       <adin:field column="Status"> 
                     <adin:val>LOAD</adin:val> 
                  </adin:field> 
                  <adin:field column="Note"> 
                     <adin:val>If any note</adin:val> 
                  </adin:field> 
 <adin:field column="Reference"> 
                     <adin:val>If any ref</adin:val> 
                  </adin:field> 
                  <adin:field column="KMTake"> 
                     <adin:val>12345</adin:val> 
                  </adin:field> 
               </adin:DataRow> 
            </adin:ModelCRUD> 
            <adin:ADLoginRequest> 
               <adin:user>' . env('ERP_USER') . '</adin:user>
                       <adin:pass>' . env('ERP_PASS') . '</adin:pass>
                       <adin:lang>192</adin:lang>
                       <adin:ClientID>' . env('ERP_CLIENT') . '</adin:ClientID>
                       <adin:RoleID>' . env('ERP_ROLE') . '</adin:RoleID>
                       <adin:OrgID>' . env('ERP_ORG') . '</adin:OrgID>
                       <adin:WarehouseID>' . env('ERP_WH') . '</adin:WarehouseID>
            </adin:ADLoginRequest> 
         </adin:ModelCRUDRequest> 
      </adin:createData> 
   </soapenv:Body> 
</soapenv:Envelope> 