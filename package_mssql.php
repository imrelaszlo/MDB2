<?php

require_once 'PEAR/PackageFileManager.php';

$version = '1.1.0';
$notes = <<<EOT
- added the listTableTriggers() method to the Manager.
- added the listViews() method to the Manager.
- aligned _modifyQuery() signature and phpdoc
- added the map datatype patch for (bug #6863)
- added support for length in reverse engineering of integer fields
- added 'result_introspection' supported metadata support
- fixed alterTable() when adding/dropping multiple columns
- properly quote table names in tableInfo() (related to bug #6573)
- use connected_server_info in getServerVersion() as a cache cache
- use parent::disconnect() in disconnect()
- added support for length in integer reverse engineering
- some fixes regarding boolean reverse engineering
- protect against sql injection in the reverse and manager module
- explicitly set is_manip parameter to false for transaction debug calls
- various minor tweaks to error messages, phpdoc and adding stub methods to the
  common driver
- typo fixes in phpdoc (thx Stoyan)
- added support for fixed and variable types for 'text' in declarations,
  as well as in reverse engineering (Request #1523)
- made _doQuery() return a reference
- added userinfo's to all raiseError calls that previously had none
- added 'prepared_statements' supported meta data setting
- limit fetch to 1 row in listTableFields()
- use setCharset() in connect()/_doConnect()
- generalized quoteIdentifier() with a property
- drop parentheses from executeStoredProc() syntax (bug #7855)
- switched most array_key_exists() calls to !empty() to improve readability and performance
- fixed a few edge cases and potential warnings
- added ability to rewrite queries for query(), exec() and prepare() using a debug handler callback
- added implementation for now() and substring() (Request #7774)
- check if result/connection has not yet been freed/dicsonnected before
  attempting to free a result set(Bug #7790)
- fix range offsets (bug #7448)
- revert change that would prefer 'clob' over 'text' for TEXT fields
  (this was breaking runtime instrospection)
- use SCOPE_IDENTITY() when version >= 8 (SQL Server 2000) otherwise fallback to
  @@IDENTITY to retrieve last inserted value (bug #7291)
- implement getServerVersion()
- removed bogus but unharmful code from mapNativeDatatype()
EOT;

$package = new PEAR_PackageFileManager();

$result = $package->setOptions(
    array(
        'packagefile'       => 'package_mssql.xml',
        'package'           => 'MDB2_Driver_mssql',
        'summary'           => 'mssql MDB2 driver',
        'description'       => 'This is the Microsoft SQL Server MDB2 driver.',
        'version'           => $version,
        'state'             => 'stable',
        'license'           => 'BSD License',
        'filelistgenerator' => 'cvs',
        'include'           => array('*mssql*'),
        'ignore'            => array('package_mssql.php'),
        'notes'             => $notes,
        'changelogoldtonew' => false,
        'simpleoutput'      => true,
        'baseinstalldir'    => '/',
        'packagedirectory'  => './',
        'dir_roles'         => array(
            'docs' => 'doc',
             'examples' => 'doc',
             'tests' => 'test',
             'tests/templates' => 'test',
        ),
    )
);

if (PEAR::isError($result)) {
    echo $result->getMessage();
    die();
}

$package->addMaintainer('davidc', 'lead', 'David Coallier', 'david@jaws.com.mx');
$package->addMaintainer('lsmith', 'lead', 'Lukas Kahwe Smith', 'smith@pooteeweet.org');
$package->addMaintainer('nrf', 'lead', 'Nathan Fredrickson', 'nathan@silverorange.com');

$package->addDependency('php', '4.3.0', 'ge', 'php', false);
$package->addDependency('PEAR', '1.0b1', 'ge', 'pkg', false);
$package->addDependency('MDB2', '2.1.0', 'ge', 'pkg', false);
$package->addDependency('mssql', null, 'has', 'ext', false);

$package->addglobalreplacement('package-info', '@package_version@', 'version');

if (array_key_exists('make', $_GET) || (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'make')) {
    $result = $package->writePackageFile();
} else {
    $result = $package->debugPackageFile();
}

if (PEAR::isError($result)) {
    echo $result->getMessage();
    die();
}
