<?php

use App\Dto\ArticleFilterDto;

class ArticleFilterDtoTest extends TestCase
{
    /**
     * Test DTO initialization
     * @return void
     */
    public function testDtoInitialization(): void
    {
        $dto = new ArticleFilterDto();

        $this->assertNull($dto->getName());
        $this->assertNull($dto->getTagsIds());
    }

    /**
     * Test setting and getting name
     * @return void
     */
    public function testSetAndGetName(): void
    {
        $dto = new ArticleFilterDto();
        $testName = 'Test Article Name';

        $dto->setName($testName);

        $this->assertEquals($testName, $dto->getName());
    }

    /**
     * Test setting name to null
     * @return void
     */
    public function testSetNameToNull(): void
    {
        $dto = new ArticleFilterDto();
        $dto->setName('Initial Name');

        $this->assertNotNull($dto->getName());

        $dto->setName(null);

        $this->assertNull($dto->getName());
    }

    /**
     * Test setting and getting empty name
     * @return void
     */
    public function testSetEmptyName(): void
    {
        $dto = new ArticleFilterDto();

        $dto->setName('');

        $this->assertEquals('', $dto->getName());
    }

    /**
     * Test setting name with whitespace
     * @return void
     */
    public function testSetNameWithWhitespace(): void
    {
        $dto = new ArticleFilterDto();
        $nameWithWhitespace = '   Test Name   ';

        $dto->setName($nameWithWhitespace);

        $this->assertEquals($nameWithWhitespace, $dto->getName());
    }

    /**
     * Test setting name with special characters
     * @return void
     */
    public function testSetNameWithSpecialCharacters(): void
    {
        $dto = new ArticleFilterDto();
        $specialName = 'Test & Article: "Advanced" (PHP)';

        $dto->setName($specialName);

        $this->assertEquals($specialName, $dto->getName());
    }

    /**
     * Test setting and getting tag IDs
     * @return void
     */
    public function testSetAndGetTagsIds(): void
    {
        $dto = new ArticleFilterDto();
        $tagIds = [1, 2, 3, 4, 5];

        $dto->setTagsIds($tagIds);

        $this->assertEquals($tagIds, $dto->getTagsIds());
        $this->assertIsArray($dto->getTagsIds());
        $this->assertCount(5, $dto->getTagsIds());
    }

    /**
     * Test setting empty tags array
     * @return void
     */
    public function testSetEmptyTagsArray(): void
    {
        $dto = new ArticleFilterDto();

        $dto->setTagsIds([]);

        $this->assertEquals([], $dto->getTagsIds());
        $this->assertIsArray($dto->getTagsIds());
        $this->assertCount(0, $dto->getTagsIds());
    }

    /**
     * Test setting single tag ID
     * @return void
     */
    public function testSetSingleTagId(): void
    {
        $dto = new ArticleFilterDto();

        $dto->setTagsIds([42]);

        $this->assertEquals([42], $dto->getTagsIds());
        $this->assertCount(1, $dto->getTagsIds());
    }

    /**
     * Test setting duplicate tag IDs
     * @return void
     */
    public function testSetDuplicateTagIds(): void
    {
        $dto = new ArticleFilterDto();
        $duplicateIds = [1, 2, 2, 3, 1, 4];

        $dto->setTagsIds($duplicateIds);

        $this->assertEquals($duplicateIds, $dto->getTagsIds());
        $this->assertCount(6, $dto->getTagsIds());
    }

    /**
     * Test setting large tag ID numbers
     * @return void
     */
    public function testSetLargeTagIds(): void
    {
        $dto = new ArticleFilterDto();
        $largeIds = [999999, 1000000, PHP_INT_MAX];

        $dto->setTagsIds($largeIds);

        $this->assertEquals($largeIds, $dto->getTagsIds());
    }

    /**
     * Test setting zero and negative tag IDs
     * @return void
     */
    public function testSetZeroAndNegativeTagIds(): void
    {
        $dto = new ArticleFilterDto();
        $mixedIds = [0, -1, 1, -100];

        $dto->setTagsIds($mixedIds);

        $this->assertEquals($mixedIds, $dto->getTagsIds());
    }

    /**
     * Test updating tag IDs
     * @return void
     */
    public function testUpdatingTagIds(): void
    {
        $dto = new ArticleFilterDto();

        $dto->setTagsIds([1, 2, 3]);
        $this->assertEquals([1, 2, 3], $dto->getTagsIds());

        $dto->setTagsIds([4, 5, 6]);
        $this->assertEquals([4, 5, 6], $dto->getTagsIds());
        $this->assertNotEquals([1, 2, 3], $dto->getTagsIds());
    }

    /**
     * Test setting both name and tag IDs
     * @return void
     */
    public function testSetBothNameAndTagIds(): void
    {
        $dto = new ArticleFilterDto();
        $testName = 'Filter Test';
        $testTagIds = [1, 5, 10];

        $dto->setName($testName);
        $dto->setTagsIds($testTagIds);

        $this->assertEquals($testName, $dto->getName());
        $this->assertEquals($testTagIds, $dto->getTagsIds());
    }

    /**
     * Test that setters don't affect each other
     * @return void
     */
    public function testSettersIndependence(): void
    {
        $dto = new ArticleFilterDto();

        $dto->setName('Test Name');
        $this->assertEquals('Test Name', $dto->getName());
        $this->assertNull($dto->getTagsIds());

        $dto->setTagsIds([1, 2, 3]);
        $this->assertEquals('Test Name', $dto->getName());
        $this->assertEquals([1, 2, 3], $dto->getTagsIds());

        $dto->setName(null);
        $this->assertNull($dto->getName());
        $this->assertEquals([1, 2, 3], $dto->getTagsIds());
    }

    /**
     * Test DTO immutability of arrays
     * @return void
     */
    public function testArrayImmutability(): void
    {
        $dto = new ArticleFilterDto();
        $originalIds = [1, 2, 3];

        $dto->setTagsIds($originalIds);

        // Modify the original array
        $originalIds[] = 4;

        // DTO should not be affected
        $this->assertEquals([1, 2, 3], $dto->getTagsIds());
        $this->assertNotEquals($originalIds, $dto->getTagsIds());
    }

    /**
     * Test getters return correct types
     * @return void
     */
    public function testGettersReturnCorrectTypes(): void
    {
        $dto = new ArticleFilterDto();

        // Test null returns
        $this->assertNull($dto->getName());
        $this->assertNull($dto->getTagsIds());

        // Test after setting values
        $dto->setName('Test');
        $dto->setTagsIds([1, 2]);

        $this->assertIsString($dto->getName());
        $this->assertIsArray($dto->getTagsIds());
    }

    /**
     * Test dto reset functionality
     * @return void
     */
    public function testDtoReset(): void
    {
        $dto = new ArticleFilterDto();

        // Set values
        $dto->setName('Test Name');
        $dto->setTagsIds([1, 2, 3]);

        $this->assertNotNull($dto->getName());
        $this->assertNotNull($dto->getTagsIds());

        // Reset to initial state
        $dto->setName(null);
        // Note: There's no explicit setTagsIds(null) method in the current implementation,
        // but we can test with empty array
        $dto->setTagsIds([]);

        $this->assertNull($dto->getName());
        $this->assertEquals([], $dto->getTagsIds());
    }

    /**
     * Test DTO with realistic data
     * @return void
     */
    public function testDtoWithRealisticData(): void
    {
        $dto = new ArticleFilterDto();

        // Simulate real usage scenario
        $dto->setName('Laravel Tutorial');
        $dto->setTagsIds([15, 23, 8]); // Assume these are real tag IDs

        $this->assertEquals('Laravel Tutorial', $dto->getName());
        $this->assertEquals([15, 23, 8], $dto->getTagsIds());
        $this->assertCount(3, $dto->getTagsIds());
    }

    /**
     * Test multiple DTO instances independence
     * @return void
     */
    public function testMultipleDtoInstancesIndependence(): void
    {
        $dto1 = new ArticleFilterDto();
        $dto2 = new ArticleFilterDto();

        $dto1->setName('First DTO');
        $dto1->setTagsIds([1, 2]);

        $dto2->setName('Second DTO');
        $dto2->setTagsIds([3, 4, 5]);

        // Verify they don't affect each other
        $this->assertEquals('First DTO', $dto1->getName());
        $this->assertEquals([1, 2], $dto1->getTagsIds());

        $this->assertEquals('Second DTO', $dto2->getName());
        $this->assertEquals([3, 4, 5], $dto2->getTagsIds());
    }
}
