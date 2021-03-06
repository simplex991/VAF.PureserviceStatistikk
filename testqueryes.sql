/* select * from Teams */
select * from Tickets
/*select * from CompanyDepartments */
select StatusId as status, count(StatusId) as antall
from Requests
group by StatusId

select status, count(status) as antall
from (select CASE StatusId
	when 1 then 'Ny'
	when 7 then 'Lukket'
	when 8 then 'Lukket'
	else 'Åpne'
	end as status
from Requests) as tabell
group by status 

/*Flere conditions i where clause*/
select status, count(status) as antall
from (select CASE StatusId
	when 1 then 'Ny'
	when 7 then 'Lukket'
	when 8 then 'Lukket'
	else 'Åpne'
	end as status
from Requests
where RestrictedTeamId IN (2, 3)) as tabell
group by status 

select * from Teams



/*Selecting CompanyDepartments [Name]*/
/* Velger Top 10 */
/*
SELECT [Name]
FROM [Pureservice3].[dbo].[CompanyDepartments] WHERE ID IN (6, 7, 8, 9, 10, 11, 12, 13, 14, 25, 31);


SELECT COUNT([Solution])
FROM [Pureservice3].[dbo].[Tickets]; */

SELECT [CompanyDepartments].[Name], [Tickets].[Solution]
FROM [Pureservice3].[dbo].[CompanyDepartments]
INNER JOIN [Pureservice3].[dbo].[Tickets]
ON [CompanyDepartments].[Id] = [Tickets].[Id]; */


/* Henter ut saker som ble lagt siste uke og hvor lang tid det er i mellom når saken ble laget og når den ble løst  */
select datediff (minute, r.Created, t.Resolved) as tid, r.Created   
from Requests r
inner join Tickets t on (r.id = t.id)
where t.Resolved is not null
   and r.RestrictedTeamId in (2, 3, 26, 34, 4, 33, 66, 37, 37, 28, 35, 29, 38)
   and r.Created > DATEADD(week, -1, getdate())
   and r.Created < DATEADD(week, 0, getdate())

/* gjør det samme som tabellen over men har med hvem opprettet saken */
select r.RequestNumber
    , r.Created
    , t.Resolved
    , datediff (minute, r.Created, t.Resolved) as tid
    , u.FullName
from Requests r
inner join Tickets t on (r.id = t.id)
inner join Users u on (t.ResolvedById = u.id)
where t.Resolved is not null
   and r.RestrictedTeamId in (2, 3, 26, 34, 4, 33, 66, 37, 37, 28, 35, 29, 38)
   and r.Created > DATEADD(week, -1, getdate())
order by tid

/* Topp 10 lengst åpne saker */
select TOP 10 $
from Requests r
inner join Tickets t on t.id = r.id
inner join Users u on u.id = r.CreatedById
where RestrictedTeamId IN(2, 3, 26, 34, 4, 33, 66, 37, 37, 28, 35, 29, 38)
and t.Solution is NULL
order by r.Created