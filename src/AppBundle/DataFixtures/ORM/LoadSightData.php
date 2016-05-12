<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Locality;
use AppBundle\Entity\Sight;
use AppBundle\Entity\SightType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSightData class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LoadSightData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'AppBundle\DataFixtures\ORM\LoadCountryData',
            'AppBundle\DataFixtures\ORM\LoadLocalityData',
            'AppBundle\DataFixtures\ORM\LoadSightTypeData',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var Locality $localityKamyanets */
        /** @var Locality $localityHotin */
        /** @var Locality $localityZaporizhia */
        /** @var Locality $localityMinsk */
        /** @var Locality $localityWarszawa */
        /** @var Locality $localityBudapest */
        $localityKamyanets  = $this->getReference('locality-Kamyanets');
        $localityHotin      = $this->getReference('locality-Hotin');
        $localityZaporizhia = $this->getReference('locality-Zaporizhia');
        $localityMinsk      = $this->getReference('locality-Minsk');
        $localityWarszawa   = $this->getReference('locality-Warszawa');
        $localityBudapest   = $this->getReference('locality-Budapest');

        /** @var SightType $sightTypeCastle */
        /** @var SightType $sightTypeThermalBath */
        /** @var SightType $sightTypeLibrary */
        /** @var SightType $sightTypeIsland */
        $sightTypeCastle      = $this->getReference('sight-type-castle');
        $sightTypeThermalBath = $this->getReference('sight-type-thermal-bath');
        $sightTypeLibrary     = $this->getReference('sight-type-library');
        $sightTypeIsland      = $this->getReference('sight-type-island');

        $sight1 = (new Sight())
            ->setName('Кам\'янець-подільська фортеця')
            ->setDescription(<<<TEXT
    Кам'яне́ць-Поді́льська форте́ця — фортеця у місті Кам'янець-Подільський (Хмельницької області України).
Відома з XIV століття як частина оборонної системи міста Кам'янець, колишньої столиці
Подільського князівства XIV–XV ст., Подільського воєводства XV–XVIII ст.,
а далі Подільської губернії (1793–1924 pp.). Є складовою частиною Національного
історико-архітектурного заповідника «Кам'янець», що належить до «Семи чудес України».
TEXT
            )
            ->setPhone('(03849)2-55-33')
            ->setWebsite('http://muzeum.in.ua/')
            ->setLatitude(48.673510)
            ->setLongitude(26.563411)
            ->setSightType($sightTypeCastle)
            ->setLocality($localityKamyanets);
        $this->setReference('sight-Kamianets-Podilskyi-castle', $sight1);
        $manager->persist($sight1);

        $sight2 = (new Sight())
            ->setName('Хотинська фортеця')
            ->setDescription(<<<TEXT
    Хотинська фортеця (рум. Cetatea Hotinului) — фортеця XIII–XVIII століть у місті Хотині на Дністрі,
що у Чернівецькій області, Україна. Сьогодні на території фортеці розташований Державний
історико-архітектурний заповідник «Хотинська фортеця». Одне з семи чудес України.
Хотинська фортеця веде свій початок від Хотинського форту, що був створений у X столітті князем
Володимиром Святославичем як одне із порубіжних укріплень південного заходу Русі, у зв'язку з
приєднанням до неї буковинських земель. Форт, який згодом було перебудовано у фортецю, розміщувався
на важливих транспортних шляхах, що з'єднували Київ із Пониззям (пізнішим Поділлям) і Придунав'ям.
TEXT
            )
            ->setPhone('(03731)2-29-32')
            ->setWebsite('http://www.hottur.org.ua/')
            ->setLatitude(48.522000)
            ->setLongitude(26.498382)
            ->setSightType($sightTypeCastle)
            ->setLocality($localityHotin);
        $this->setReference('sight-Hotin', $sight2);
        $manager->persist($sight2);

        $sight3 = (new Sight())
            ->setName('острів Хортиця')
            ->setDescription(<<<TEXT
    Хо́ртиця — найбільший острів на Дніпрі, розташований у районі міста Запоріжжя, нижче Дніпрогесу.
Унікальний природний та історичний комплекс. Хортиця є одним із Семи чудес України. На північній
стороні острова був останній дніпровський поріг. Хортиця витягнута із північого-заходу на
південний-схід, має довжину 12,5 км, ширину в середньому 2,5 км і площу приблизно 3000 га.
Острів до останнього часу зберігав ліси в прибережних балках, а в післявоєнні часи був заліснений
лісовим господарством в північній частині, де ґрунти є піщаними. В південній частині зберігається
степ з багатьма реліктовими видами рослин, які збереглися тільки на острові, але в давнину зростали
на всій території півдня України. На крайньому півдні острова існують плавні.
TEXT
            )
            ->setPhone('+38(095)914-77-06')
            ->setWebsite('http://hortica.zp.ua/')
            ->setLatitude(47.831332)
            ->setLongitude(35.087736)
            ->setSightType($sightTypeIsland)
            ->setLocality($localityZaporizhia);
        $this->setReference('sight-Hortitsa', $sight3);
        $manager->persist($sight3);

        $sight4 = (new Sight())
            ->setName('Національна бібліотека Білорусі')
            ->setDescription(<<<TEXT
Національна бібліотека Білорусі (біл. Нацыянальная бібліятэка Беларусі) — найбільша наукова бібліотека
країни, провідний бібліотечно-інформаційний, соціокультурний та соціополітичний центр Білорусі.
Заснована 15 вересня 1922 р. як білоруська державна та університетська бібліотека. Розташована у Мінську.
Згідно з указом Президента республіки Білорусь Олександра Лукашенка 1 листопада 2002 р. розпочалося
будівництво нового приміщення для Бібліотеки. Нова будівля була здана в експлуатацію 16 червня 2006 р.
Вона являє собою ромбокубооктаедр («діамант») висотою 73,7 м. (20 поверхів) та вагою 115 000 тонн.
TEXT
            )
            ->setPhone('(+375 17) 266 37 37')
            ->setWebsite('http://www.nlb.by/')
            ->setLatitude(53.931502)
            ->setLongitude(27.646043)
            ->setSightType($sightTypeLibrary)
            ->setLocality($localityMinsk);
        $this->setReference('sight-Minsk-library', $sight4);
        $manager->persist($sight4);

        $sight5 = (new Sight())
            ->setName('Королівський замок у Варшаві')
            ->setDescription(<<<TEXT
Королі́вський за́мок у Варша́ві — бароко-класичний королівський замок, розташований на Замковій площі у Варшаві.
Розташування Королівського замку на замковій площі. Ліворуч - Колона короля Сигізмунда
Спочатку він виконував функції резиденції династії Мазовецьких П'ястів, а від XVI століття — садиба владарів Першої
Речі Посполитої: Короля і Сейму (Ізби Посольської та Сенату). У XIX столітті, після закінчення Листопадового
повстання 1830–1831 років, замок був переданий на потреби російської адміністрації.
Під час І Світової війни слугував резиденцією німецького генерала-губернатора. В період з 1920 по 1922 — садиба
Голови держави Другої Речі Посполитої. 1926–1939 — резиденція Президента Другої Речі Посполитої.
Замок, спалений і пограбований німцями у 1939 році, був дощенту знищений у 1944. У 1971 році відбудований та
реконструйований. Королівський замок носить статус пам'ятки історії та культури Польщі.
На даний час використовується як музей (входить до Польського реєстру музеїв).
TEXT
            )
            ->setPhone('(+48 22) 35 55 170')
            ->setWebsite('https://www.zamek-krolewski.pl/')
            ->setLatitude(52.247969)
            ->setLongitude(21.015481)
            ->setSightType($sightTypeCastle)
            ->setLocality($localityWarszawa);
        $this->setReference('sight-Warszawa-castle', $sight5);
        $manager->persist($sight5);

        $sight6 = (new Sight())
            ->setName('Купальні Сечені')
            ->setDescription(<<<TEXT
Купальня Сечені - це найбільша у всій Європі термальна купальня. Знаходиться вона в самому центрі мальовничого
будапештського парку Варошлігет, в оточенні чудової краси місцевості з парковими деревами.Купальня Сечені є
найпопулярнішою купальнею не тільки серед гостей Будапешта, а й серед його постійних жителів.
TEXT
            )
            ->setPhone('(+36-1)363-3210')
            ->setWebsite('http://www.szechenyifurdo.hu/')
            ->setLatitude(47.518881)
            ->setLongitude(19.082358)
            ->setSightType($sightTypeThermalBath)
            ->setLocality($localityBudapest);
        $this->setReference('sight-Szechenyi', $sight6);
        $manager->persist($sight6);

        $manager->flush();
    }
}
