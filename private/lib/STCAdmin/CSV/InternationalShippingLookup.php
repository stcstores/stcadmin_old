<?php
namespace STCAdmin\CSV;

class InternationalShippingLookup extends CSVFile {
    public function __construct()
    {
        $this->path = dirname(__FILE__) . '/';
        $this->filename = 'international_shipping_lookup.csv';
        $this->filepath = $this->path . '/' . $this->filename;
        $this->header = ['weight', 'fr', 'de', 'eu', 'usa', 'aus', 'row'];
    }

    public function getInternationalShipping($weight)
    {
        $table = $this->read();
        $i = 1;
        if ($weight > $table['weight'][1]) {
            while ($table['weight'][$i] < $weight) {
                $i++;
            }

            $i --;
        }

        $weights = array();
        $weights['fr'] = $table['fr'][$i];
        $weights['de'] = $table['de'][$i];
        $weights['eu'] = $table['eu'][$i];
        $weights['usa'] = $table['usa'][$i];
        $weights['aus'] = $table['aus'][$i];
        $weights['row'] = $table['row'][$i];

        return $weights;
    }
}
