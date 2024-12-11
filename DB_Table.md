## Table Descriptions

1. **LOCKER Table**
   - Manages locker number, member ID, rental start date, end date, and availability.
   - Availability is indicated as `Y` (available) or `N` (unavailable).
   - `MEMBER_ID` is set as a foreign key linking to the MEMBER table.

2. **MEMBER Table**
   - Manages member ID, membership code, locker number, password, name, gender, phone number, and registration date.
   - Gender is restricted to `M` (male) or `F` (female).
   - Membership code and locker number are linked to the MEMBERSHIP and LOCKER tables, respectively.

3. **PT Table**
   - Manages PT code, trainer ID, price, and PT type.
   - Trainer ID is a foreign key linked to the TRAINER table.

4. **TRAINER Table**
   - Manages trainer ID, password, name, gender, phone number, and registration date.
   - Gender is restricted to `M` (male) or `F` (female).

5. **MEMBER_PT Table**
   - Manages member ID, PT code, PT count, and registration date.
   - Member ID and PT code are linked to the MEMBER and PT tables, respectively.

6. **MEMBERSHIP Table**
   - Manages membership code, duration (in months), and price.

---



## Create Table Code


### LOCKER Table
```sql
CREATE TABLE "DB501_PROJ_G1"."LOCKER"
(
    "LOCKER_NUMBER" NUMBER(5,0),
    "MEMBER_ID" VARCHAR2(50),
    "START_DATE" DATE,
    "END_DATE" DATE,
    "AVAILABILITY" CHAR(1) DEFAULT 'N',
    CHECK (AVAILABILITY IN ('Y', 'N')) ENABLE,
    PRIMARY KEY ("LOCKER_NUMBER") USING INDEX ENABLE,
    CONSTRAINT "FK_LOCKER_MEMBER" FOREIGN KEY ("MEMBER_ID")
    REFERENCES "DB501_PROJ_G1"."MEMBER" ("MEMBER_ID") ON DELETE SET NULL ENABLE
);
```

### MEMBER Table

```sql
CREATE TABLE "DB501_PROJ_G1"."MEMBER"
(
    "MEMBER_ID" VARCHAR2(50),
    "MEMBERSHIP_CODE" NUMBER(10,0),
    "LOCKER_NUMBER" NUMBER(5,0),
    "MEMBER_PWD" VARCHAR2(50),
    "NAME" VARCHAR2(50) NOT NULL ENABLE,
    "GENDER" VARCHAR2(1),
    "MEMBER_PHONE_NUMBER" VARCHAR2(15),
    "REGISTER_DATE" DATE DEFAULT SYSDATE NOT NULL ENABLE,
    CHECK (GENDER IN ('M', 'F')) ENABLE,
    PRIMARY KEY ("MEMBER_ID"),
    CONSTRAINT "FK_MEMBER_MEMBERSHIP" FOREIGN KEY ("MEMBERSHIP_CODE")
    REFERENCES "DB501_PROJ_G1"."MEMBERSHIP" ("MEMBERSHIP_CODE") ON DELETE SET NULL ENABLE,
    CONSTRAINT "FK_MEMBER_LOCKER" FOREIGN KEY ("LOCKER_NUMBER")
    REFERENCES "DB501_PROJ_G1"."LOCKER" ("LOCKER_NUMBER") ENABLE
);
```

### PT Table

```sql
CREATE TABLE "DB501_PROJ_G1"."PT"
(
    "PT_CODE" NUMBER(10,0),
    "TRAINER_ID" VARCHAR2(50),
    "PRICE" NUMBER(10,2) NOT NULL ENABLE,
    "PT_TYPE" VARCHAR2(50) NOT NULL ENABLE,
    PRIMARY KEY ("PT_CODE"),
    CONSTRAINT "FK_PT_TRAINER" FOREIGN KEY ("TRAINER_ID")
    REFERENCES "DB501_PROJ_G1"."TRAINER" ("TRAINER_ID") ENABLE
);
```

### TRAINER Table

```sql
CREATE TABLE "DB501_PROJ_G1"."TRAINER"
(
    "TRAINER_ID" VARCHAR2(50),
    "TRAINER_PWD" VARCHAR2(50),
    "NAME" VARCHAR2(50) NOT NULL ENABLE,
    "GENDER" VARCHAR2(1),
    "TRAINER_PHONE_NUMBER" VARCHAR2(15),
    "REGISTER_DATE" DATE DEFAULT SYSDATE NOT NULL ENABLE,
    CHECK (GENDER IN ('M', 'F')) ENABLE,
    PRIMARY KEY ("TRAINER_ID")
);
```

### MEMBER_PT Table

```sql
CREATE TABLE "DB501_PROJ_G1"."MEMBER_PT"
(
    "MEMBER_ID" VARCHAR2(50),
    "PT_CODE" NUMBER(10,0),
    "PT_COUNT" NUMBER(3,0) NOT NULL ENABLE,
    "REGISTER_DATE" DATE DEFAULT SYSDATE NOT NULL ENABLE,
    PRIMARY KEY ("MEMBER_ID", "PT_CODE"),
    CONSTRAINT "FK_MEMBER_PT_PT" FOREIGN KEY ("PT_CODE")
    REFERENCES "DB501_PROJ_G1"."PT" ("PT_CODE") ENABLE,
    CONSTRAINT "FK_MEMBER_PT_MEMBER" FOREIGN KEY ("MEMBER_ID")
    REFERENCES "DB501_PROJ_G1"."MEMBER" ("MEMBER_ID") ON DELETE CASCADE ENABLE
);
```

### MEMBERSHIP Table

```sql
CREATE TABLE "DB501_PROJ_G1"."MEMBERSHIP"
(
    "MEMBERSHIP_CODE" NUMBER(10,0),
    "MONTH" VARCHAR2(50) NOT NULL ENABLE,
    "PRICE" NUMBER(10,2) NOT NULL ENABLE,
    PRIMARY KEY ("MEMBERSHIP_CODE")
);
```