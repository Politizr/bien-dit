<?php

/**
 * Add gmaps columns for managing geoloc w. Gmaps picker
 *
 * @author Studio Echo / Lionel Bouzonville
 */
class StudioEchoGmapsBundleBehavior extends Behavior {

    // default parameters value
    protected $parameters = array(
        'gm_lat_column' => 'gm_lat',
        'gm_lng_column' => 'gm_lng',
        'gm_street_number_column' => 'gm_street_number',
        'gm_route_column' => 'gm_route',
        'gm_locality_column' => 'gm_locality',
        'gm_administrative_area_level_2_column' => 'gm_administrative_area_level_2',
        'gm_administrative_area_level_1_column' => 'gm_administrative_area_level_1',
        'gm_country_column' => 'gm_country',
        'gm_postal_code_column' => 'gm_postal_code',
        'gm_type_column' => 'gm_type',
    );

    /**
     * Add gmaps cols to the current table:
     * gm_lat, gm_lng, gm_street_number, gm_route, gm_locality, gm_administrative_area_level_2, gm_administrative_area_level_1, 
     * gm_country, gm_postal_code, gm_type
     */
    public function modifyTable()
    {
        if (!$this->getTable()->containsColumn($this->getParameter('gm_lat_column'))) {
            $this->getTable()->addColumn(array(
                'name' => $this->getParameter('gm_lat_column'),
                'type' => 'VARCHAR',
                'size' => 250
            ));
        }
        if (!$this->getTable()->containsColumn($this->getParameter('gm_lng_column'))) {
            $this->getTable()->addColumn(array(
                'name' => $this->getParameter('gm_lng_column'),
                'type' => 'VARCHAR',
                'size' => 250
            ));
        }
        if (!$this->getTable()->containsColumn($this->getParameter('gm_street_number_column'))) {
            $this->getTable()->addColumn(array(
                'name' => $this->getParameter('gm_street_number_column'),
                'type' => 'VARCHAR',
                'size' => 250
            ));
        }
        if (!$this->getTable()->containsColumn($this->getParameter('gm_route_column'))) {
            $this->getTable()->addColumn(array(
                'name' => $this->getParameter('gm_route_column'),
                'type' => 'VARCHAR',
                'size' => 250
            ));
        }
        if (!$this->getTable()->containsColumn($this->getParameter('gm_locality_column'))) {
            $this->getTable()->addColumn(array(
                'name' => $this->getParameter('gm_locality_column'),
                'type' => 'VARCHAR',
                'size' => 250
            ));
        }
        if (!$this->getTable()->containsColumn($this->getParameter('gm_administrative_area_level_2_column'))) {
            $this->getTable()->addColumn(array(
                'name' => $this->getParameter('gm_administrative_area_level_2_column'),
                'type' => 'VARCHAR',
                'size' => 250
            ));
        }
        if (!$this->getTable()->containsColumn($this->getParameter('gm_administrative_area_level_1_column'))) {
            $this->getTable()->addColumn(array(
                'name' => $this->getParameter('gm_administrative_area_level_1_column'),
                'type' => 'VARCHAR',
                'size' => 250
            ));
        }
        if (!$this->getTable()->containsColumn($this->getParameter('gm_country_column'))) {
            $this->getTable()->addColumn(array(
                'name' => $this->getParameter('gm_country_column'),
                'type' => 'VARCHAR',
                'size' => 250
            ));
        }
        if (!$this->getTable()->containsColumn($this->getParameter('gm_postal_code_column'))) {
            $this->getTable()->addColumn(array(
                'name' => $this->getParameter('gm_postal_code_column'),
                'type' => 'VARCHAR',
                'size' => 250
            ));
        }
        if (!$this->getTable()->containsColumn($this->getParameter('gm_type_column'))) {
            $this->getTable()->addColumn(array(
                'name' => $this->getParameter('gm_type_column'),
                'type' => 'VARCHAR',
                'size' => 250
            ));
        }
    }
}

