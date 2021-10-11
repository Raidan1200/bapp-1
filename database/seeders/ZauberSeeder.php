<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use App\Models\Venue;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZauberSeeder extends Seeder
{
    public $zauber_json = [
        'company' => 'i(s)s anders Catering & Event GmbH',
        'street' => 'Schützenhöhe',
        'street_no' => '11',
        'zip' => '01099',
        'city' => 'Dresden',
        'phone' => '0351 8053280',
        'fax' => '0351 8053281',
        'email' => 'info@huettenzauber-dresden.de',
        'web' => 'www.huettenzauber-dresden.de',
        'bank' => 'Ostsächsische Sparkasse Dresden',
        'iban' => 'DE94 8505 0300 0221 0877 96',
        'bic' => 'OSDDDE81XXX',
        'manager' => 'Nico Thierbach',
        'tax_id' => '202/111/06930',
        'hrb' => '34457',
        'court' => 'Amtsgericht Dresden',
    ];

    public function run()
    {
        // Venue 1
        $v1 = Venue::create([
            'name' => 'Hüttenzauber',
            'slug' => 'zauber',
            'email' => 'info@huettenzauber.de',
            'logo' => null, // TODO
            'invoice_blocks' => $this->zauber_json,
            'reminder_delay' => 5,
            'check_delay' => 7,
            'cancel_delay' => 12,
        ]);

        $v1->createToken('api-token')->plainTextToken;
        // 1|eZiKXxZPJdfWbtKQdc5kzycRwUSelVVB7sV4Aghq
        DB::update('update personal_access_tokens set token = ? where id = ?', ['cb12ad7fc5529975b3d7130f5aa9cfdac47defeb29fb31c374590f2d1b85ac6f', 1]);

        $admin = User::where('email', 'admin@bapp.de')->first();
        $v1->users()->attach([$admin->id]);

        $room1 = Room::create([
            'name' => 'Hüttenrestaurant',
            'slug' => 'huettenrestaurant',
            'slogan' => 'Hier werden Sie verwöhnt',
            'description' => '<p>In unserem Hüttenrestaurant bieten wir Ihnen im rustikal-modern gehaltenen Ambiente Sitzplätze für bis zu 100 Personen. Urig und gemütlich können sie ihre Weihnachtsfeier auf knapp 200m² genießen. In geselliger Runde am knisternden Kaminfeuer  verwöhnen wir sie mit kulinarischen Leckerbissen.<br></p>',
            'image' => 'https://buchung.dresdner-huettenzauber.de/images/huettenrestaurant.jpg',
            'capacity' => 150,
            'venue_id' => $v1->id
        ]);

        $room2 = Room::create([
            'name' => 'Curlingbahn',
            'slug' => 'curlingbahn',
            'slogan' => 'Das Event für Jung und Alt',
            'description' => '<p>Wer kennt das nicht, ohne Ziel zappen wir durch die Programme am Fernseher und auf einmal rutschen und wischen wildgewordene Eisprinzessinnen und Eisprinzen mit so einem runden Ding über die Eisbahn. <br>Ganz genau: DAS ist Curling oder Eisstockschießen...<br>Und weil es uns vom Dresdner Hüttenzauber schon oft so ging und wir fasziniert vor der Mattscheibe zusahen, wollten wir genau das bei uns auf dem Dresdner Hüttenzauber! <br>Einziger Unterschied: Da in Deutschland die Sicherheit oberstes Gebot hat, haben wir keine Eisfläche, sondern eine WM- Kunstharzbahn, macht aber genauso viel Spaß. <br>Unser Team hat\'s ausgetestet und der Bahn den Stempel "Für gut befunden" aufgedrückt :)<br><br>Firmenfeiern, Familien oder einfach Turniere unter Freunden, hier kann jeder mitmachen. <br>Eine Bahn kann mit bis zu 8 Personen gemietet werden. <br>Genießen Sie 60 Minuten auf unserer Curlingbahn. <br>Sie erhalten eine fachliche Einweisung (keine Vorkenntnisse erforderlich) und bekommen das nötige Spielzubehör gestellt..<br><br>Preis pro Bahn: 49 € für bis zu 8 Personen</p>',
            'image' => 'https://buchung.dresdner-huettenzauber.de/images/curlingbahn.jpg',
            'capacity' => 8,
            'venue_id' => $v1->id
        ]);

        $p1 = Package::create([
            'name' => 'Hüttenpaket Classic',
            'slug' => 'huettenpaket-classic',
            'slogan' => 'Herzlich willkommen zu ihrer Weihnachtsfeier beim Dresdner Hüttenzauber im gemütlichen, rustikalen Hüttenrestaurant!',
            'description' => '<p>Herzlich willkommen zu ihrer Weihnachtsfeier beim Dresdner Hüttenzauber im gemütlichen, rustikalen Hüttenrestaurant!<br /><br />Genießen Sie einen abwechslungsreichen Abend in unserem neu gestalteten gemütlichen Hüttenrestaurant. Genießen einen stimmungsvollen Abend im urigen Ambiente. Zusätzlich haben Sie die Möglichkeit einen heiteren Aktivteil auf einer unserer 4 Eisstockbahnen durchzuführen. Diese können Sie gern optional nach Wahl des Hüttenpaketes hinzu buchen.<br /><br />Ihr persönliches Hüttenpaket:<br /><ul><li>gemütliche Hüttenatmosphäre im Kerzenschein</li><li>leckeres 3-Gang Menü:<h5><b>1. Gang</b></h5>Hausgemachtes Karotten-Ingwersüppchen mit Orange<h5><b>2. Gang</b></h5>Knusprige Entenkeule an einer Beifußsoße, mit Apfelrotkohl und Kartoffelklößen<br>oder eines unserer typisch-alpenländischen Hüttengerichte nach Wahl<h5><b>3. Gang</b></h5>leckeres Hüttendessert zum Abschluss </li></ul><br /><br />28.50€ inkl. gesetzl Mwst.<br />Preis gilt pro Person</p>',
            'image' => 'https://buchung.dresdner-huettenzauber.de/images/paket1.jpg',
            'starts_at' => '2021-11-22 00:00:00',
            'ends_at' => '2021-12-27 23:59:59',
            'opens_at' => '12:00:00',
            'closes_at' => '23:00:00',
            'min_occupancy' => 30,
            'unit_price' => 2850,
            'vat' => 19,
            'deposit' => 20,
            'is_flat' => false,
            'price_flat' => null,
            'vat_flat' => null,
            'deposit_flat' => null,
            'venue_id' => $v1->id
        ]);

        $p1->rooms()->sync([$room1->id]);

        $p2 = Package::create([
            'name' => 'Hüttenpaket Premium',
            'slug' => 'huettenpaket-premium',
            'slogan' => 'Herzlich willkommen zu ihrer Weihnachtsfeier beim Dresdner Hüttenzauber im gemütlichen, rustikalen Hüttenrestaurant!',
            'description' => '<p>Herzlich willkommen zu ihrer Weihnachtsfeier beim Dresdner Hüttenzauber im gemütlichen, rustikalen Hüttenrestaurant!<br /><br />Genießen Sie einen abwechslungsreichen Abend in unserem neu gestalteten gemütlichen Hüttenrestaurant. Genießen einen stimmungsvollen Abend im urigen Ambiente. Zusätzlich haben Sie die Möglichkeit einen heiteren Aktivteil auf einer unserer 4 Eisstockbahnen durchzuführen. Diese können Sie gern optional nach Wahl des Hüttenpaketes hinzu buchen.<br /><br />Ihr persönliches Hüttenpaket:<br /><ul><li>gemütliche Hüttenatmosphäre im Kerzenschein</li><li>leckeres 3-Gang Menü:<h5><b>1. Gang</b></h5>Hausgemachtes Karotten-Ingwersüppchen mit Orange<h5><b>2. Gang</b></h5>Knusprige Entenkeule an einer Beifußsoße, mit Apfelrotkohl und Kartoffelklößen<br>oder eines unserer typisch-alpenländischen Hüttengerichte nach Wahl<h5><b>3. Gang</b></h5>leckeres Hüttendessert zum Abschluss<br /> </li><li><h5><b>inklusive Getränkeflat</b></h5> im Hüttenrestaurant mit Benediktiner, Wernesgrüner, Weine, Secco & alkoholfreien Getränken<br>(zusätzliche Getränke wie Spirituosen und Mixgetränke werden nach Verbrauch abgerechnet) ab 22:00 Uhr werden alle Getränke nach Verbrauch abgerechnet</li></ul><br /><br />58.90€ inkl. gesetzl Mwst.<br />Preis gilt pro Person</p>',
            'image' => 'https://buchung.dresdner-huettenzauber.de/images/paket2.jpg',
            'starts_at' => '2021-11-22 00:00:00',
            'ends_at' => '2021-12-27 23:59:59',
            'opens_at' => '12:00:00',
            'closes_at' => '23:00:00',
            'min_occupancy' => 30,
            'unit_price' => 5890,
            'vat' => 7,
            'deposit' => 20,
            'is_flat' => true,
            'price_flat' => null,
            'vat_flat' => null,
            'deposit_flat' => null,
            'venue_id' => $v1->id
        ]);

        $p2->rooms()->sync([$room1->id]);

        $p3 = Package::create([
            'name' => 'Curlingbahn',
            'slug' => 'curlingbahn',
            'slogan' => 'Das Event für Jung und Alt',
            'description' => '',
            'image' => null,
            'starts_at' => '2021-11-22 00:00:00',
            'ends_at' => '2021-12-27 23:59:59',
            'opens_at' => '12:00:00',
            'closes_at' => '21:00:00',
            'min_occupancy' => 1,
            'unit_price' => 4900,
            'vat' => 19,
            'deposit' => 100,
            'is_flat' => false,
            'price_flat' => null,
            'vat_flat' => null,
            'deposit_flat' => null,
            'venue_id' => $v1->id
        ]);

        $p3->rooms()->sync([$room2->id]);

        Product::create([
            'name' => 'Bier, gezapft, klein',
            'note' => null,
            'unit_price' => 290,
            'vat' => 20,
            'venue_id' => $v1->id
        ]);

        Product::create([
            'name' => 'Bier, gezapft, groß',
            'note' => null,
            'unit_price' => 410,
            'vat' => 20,
            'venue_id' => $v1->id
        ]);

        Product::create([
            'name' => 'Champagner, Flasche',
            'note' => null,
            'unit_price' => 1750,
            'vat' => 20,
            'venue_id' => $v1->id
        ]);
    }
}
