<!DOCTYPE html>
<html>
<body>
    <h2>Tester une réservation EduReserve</h2>
    <form action="/nouvelle-reservation" method="POST">
        <!-- Le jeton de sécurité obligatoire de Laravel -->
        @csrf 
        
        <input type="hidden" name="user_id" value="1">
        <input type="hidden" name="salle_id" value="1">
        
        <label>Date :</label>
        <input type="date" name="date" value="2026-10-24"><br><br>
        
        <label>Heure de début :</label>
        <input type="time" name="heure_debut" value="08:00"><br><br>
        
        <label>Heure de fin :</label>
        <input type="time" name="heure_fin" value="10:00"><br><br>
        
        <button type="submit">Réserver la salle</button>
    </form>
</body>
</html>